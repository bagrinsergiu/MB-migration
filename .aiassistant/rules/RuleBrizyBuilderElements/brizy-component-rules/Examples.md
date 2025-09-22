---
apply: always
---

Here’s the full content of your `Examples.md` translated into English while preserving structure and formatting:

```
# BrizyComponent — Usage Examples

Below are practical scenarios for building Brizy component trees and applying styling helpers.  
All examples are in PHP and assume the namespaces used in the project.

Import classes:

```

use MBMigration\Builder\BrizyComponent{BrizyComponent, BrizyRowComponent, BrizyColumComponent, BrizyLineComponent, BrizyWrapperComponent, BrizyImageComponent, BrizyEmbedCodeComponent};

```

## 1) Simple row with one column and a line

```

\$row = new BrizyRowComponent();
\$col = new BrizyColumComponent(null, \$row);
\$line = new BrizyLineComponent(null, \$col);

\$row->getValue()->add('items', \$col);
\$col->getValue()->add('items', \$line);

// Small styles
\$row->addGroupedPadding(20, 'padding', 'px')
->addBgColor('#eeeeee', 1);

```

## 2) Two columns with different content

```

\$row = new BrizyRowComponent();
\$left = new BrizyColumComponent(null, \$row);
\$right = new BrizyColumComponent(null, \$row);

\$row->getValue()->add('items', \[\$left, \$right]);

// Add Wrapper and Line to the left column
\$wrapLeft = new BrizyWrapperComponent('wrapper--richText', \$left);
\$lineLeft = new BrizyLineComponent(null, \$wrapLeft);
\$left->getValue()->add('items', \[\$wrapLeft]);
\$wrapLeft->getValue()->add('items', \[\$lineLeft]);

// In the right one — an image
\$img = new BrizyImageComponent();
\$right->getValue()->add('items', \$img);

// Slightly adjust right padding
\$right->addPaddingRight(15, 'px');

```

## 3) Inserting by positions (start, middle, negative index)

```

\$col = new BrizyColumComponent();
\$lineA = new BrizyLineComponent(null, \$col);
\$lineB = new BrizyLineComponent(null, \$col);
\$lineC = new BrizyLineComponent(null, \$col);

// Add to the end (default)
\$col->getValue()->add('items', \$lineA);

// Insert at the beginning
\$col->getValue()->add('items', \$lineB, 0);

// Insert before the last element (negative index -1)
\$col->getValue()->add('items', \$lineC, -1);

```

## 4) Magic methods set_*/get_*/add_* on value

```

\$row = new BrizyRowComponent();
\$value = \$row->getValue();

// Equivalent to \$value->set('tabsState', 'normal');
\$value->set\_tabsState('normal');

// Equivalent to \$value->add('items', \$child, null);
\$child = new BrizyColumComponent(null, \$row);
\$value->add\_items(\$child);

// Get field
\$tabsState = \$value->get\_tabsState();

```

## 5) Fine-tuning typography and sizes

```

\$row = new BrizyRowComponent();
\$row->titleTypography()                        // heading preset
->addFont(18, 'Arial', 'system', 600, 1.3) // font/weight/line height
->mobileSizeTypeOriginal()                 // mobile size — original
->tabletSizeTypeOriginal()                 // tablet size — original
->addHeight(420, 'px');                    // section/element height

```

## 6) Embedding code

```

\$embed = new BrizyEmbedCodeComponent('<div>My widget</div>');
// Can be added to any column/row
\$col = new BrizyColumComponent();
\$col->getValue()->add('items', \$embed);

```

## 7) Using the fromArray factory

```

\$data = \[
'type' => 'Row',
'value' => \[
'\_styles' => \['row'],
'items' => \[
\[ 'type' => 'Column', 'value' => \['\_styles' => \['column'], 'items' => \[]] ]
]
]
];

\$row = BrizyComponent::fromArray(\$data);

```

## 8) Adding custom CSS

```

\$row = new BrizyRowComponent();
\$row->addCustomCSS('.my-row { border: 1px solid red; }');

```

## 9) Mobile/tablet padding and alignment

```

\$col = new BrizyColumComponent();
\$col->addMobilePadding(12)
->addTabletMargin(20)
->addMobileHorizontalContentAlign('center')
->addVerticalContentAlign('middle');

```

## 10) Quick assembly via builder

```

\$builder = /\* get from ThemeContext \*/ \$ctx->getBrizyComponentBuilder();
\$section = \$builder->createSection();
\$row = \$builder->createRow();

// Then add the row to the section according to the ComponentSection API

```
```
