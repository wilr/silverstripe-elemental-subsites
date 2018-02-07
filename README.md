# SilverStripe Elemental Subsites

This module adds [subsite](https://github.com/silverstripe/silverstripe-subsites) support for 
[elemental](https://github.com/dnadesign/silverstripe-elemental).

```yaml
ElementPage:
  extensions:
    - DNADesign\ElementalSubsites\Extensions\ElementalSubsitePageExtension

DNADesign\Elemental\Models\BaseElement:
  extensions:
    - DNADesign\ElementalSubsites\Extensions\ElementSubsiteExtension
```
