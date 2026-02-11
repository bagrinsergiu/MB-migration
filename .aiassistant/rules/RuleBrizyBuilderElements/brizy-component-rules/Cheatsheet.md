---
apply: on_demand
---

# BrizyComponent — Cheatsheet (Quick Reference)

Imports:
- use MBMigration\Builder\BrizyComponent\BrizyComponent;
- use MBMigration\Builder\BrizyComponent\{BrizyRowComponent, BrizyColumComponent, BrizyLineComponent, BrizyWrapperComponent, BrizyImageComponent, BrizyEmbedCodeComponent};

Creation:
- $row = new BrizyRowComponent();
- $col = new BrizyColumComponent(null, $row);
- $line = new BrizyLineComponent(null, $col);
- $wrap = new BrizyWrapperComponent('wrapper--richText');
- $img = new BrizyImageComponent();
- $embed = new BrizyEmbedCodeComponent('<div/>');
- $any = BrizyComponent::fromArray($dataArray, $parent);

Working with items:
- $comp->getValue()->add('items', $child); // to the end
- $comp->getValue()->add('items', $child, 0); // to the beginning
- $comp->getValue()->add('items', [$a, $b]); // batch
- $comp->getValue()->add_items($child, -1); // magic, before the last
- $items = $comp->getValue()->get('items');

Typography / size / color:
- $c->titleTypography();
- $c->addFont(16, 'Arial', 'system', 600, 1.3);
- $c->addBgColor('#ffffff', 1);
- $c->addHeight(400, 'px');
- $c->mobileSizeTypeOriginal()->tabletSizeTypeOriginal();

Padding / margins:
- $c->addGroupedPadding(20, 'padding', 'px');
- $c->addPadding(10, 15, 10, 15, 'padding', 'px');
- $c->addGroupedMargin(30, 'margin', 'px');
- $c->addMobilePadding(12);
- $c->addTabletMargin(20);

Alignment:
- $c->addVerticalContentAlign('middle');
- $c->addHorizontalContentAlign('center');
- $c->addMobileHorizontalContentAlign('center');

Other:
- $c->addCustomCSS('.class { ... }');
- $c->addLine(40, ['hex'=>'#000','opacity'=>1], 3, $opts, null, 'center');
- $c->addRow([$col1, $col2], null, $opts);

Debugging:
- json_encode($component); // check structure
- Logs: MBMigration\Core\Logger

Extending types:
- Create Brizy<MyType>Component, add a branch in BrizyComponent::fromArray.
