<?php

namespace DNADesign\ElementalSubsites\Extensions;

use DNADesign\Elemental\Models\BaseElement;
use DNADesign\Elemental\Models\ElementalArea;

use SilverStripe\Core\Config\Config;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DB;
use SilverStripe\Versioned\Versioned;


/**
 * @package elemental
 */
class ElementalSubsitePageExtension extends DataExtension
{

    /**
     * If the page is duplicated across subsites, copy the elements across too.
     *
     * @return Page The duplicated page
     */
    public function onAfterDuplicateToSubsite($originalPage)
    {
        $originalElementalArea = $originalPage->getComponent('ElementalArea');
        $duplicateElementalArea = $originalElementalArea->duplicate(false);
        $duplicateElementalArea->write();
        $this->owner->ElementalAreaID = $duplicateElementalArea->ID;
        $this->owner->write();

        foreach ($originalElementalArea->Items() as $originalElement) {
            $duplicateElement = $originalElement->duplicate(true);

            // manually set the ParentID of each element, so we don't get versioning issues
            DB::query(sprintf("UPDATE Element SET ParentID = %d WHERE ID = %d", $duplicateElementalArea->ID, $duplicateElement->ID));
        }
    }
}
