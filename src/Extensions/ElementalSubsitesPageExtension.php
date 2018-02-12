<?php

namespace DNADesign\ElementalSubsites\Extensions;

use DNADesign\Elemental\Models\BaseElement;
use DNADesign\Elemental\Models\ElementalArea;
use Page;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DB;

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
        /** @var ElementalArea $originalElementalArea */
        $originalElementalArea = $originalPage->getComponent('ElementalArea');

        $duplicateElementalArea = $originalElementalArea->duplicate(false);
        $duplicateElementalArea->write();

        $this->owner->ElementalAreaID = $duplicateElementalArea->ID;
        $this->owner->write();

        foreach ($originalElementalArea->Items() as $originalElement) {
            /** @var BaseElement $originalElement */
            $duplicateElement = $originalElement->duplicate(true);

            // manually set the ParentID of each element, so we don't get versioning issues
            DB::query(
                sprintf(
                    "UPDATE %s SET ParentID = %d WHERE ID = %d",
                    DataObject::getSchema()->tableName(BaseElement::class),
                    $duplicateElementalArea->ID,
                    $duplicateElement->ID
                )
            );
        }
    }
}
