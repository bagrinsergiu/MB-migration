<?php

use MBMigration\Builder\Layout\Theme\Anthem\Elements\Element;
use PHPUnit\Framework\TestCase;

class ElementTest extends TestCase 
{
    /**
     * This is the test case for the method hasAnyTagsInsidePTag
     * The method checks if there are any tags inside a p tag except for those specified in the ignoreTags
     */
    public function testHasAnyTagsInsidePTag()
    {
        $element = new ElementT();

        // Test case 1: p tag with no inner tags
        $html = "<p><br></p>";
        $this->assertFalse($element->hasAnyTagsInsidePTag($html));

        $html = "<p></p>";
        $this->assertFalse($element->hasAnyTagsInsidePTag($html));

        // Test case 2: p tag with no inner tags
        $html = '<p class="brz-fs-lg-46 brz-ff-yxliyccigeyavbkozixanwqgxarvhbfyywfq brz-ft-upload brz-fw-lg-300 brz-lh-lg-1_3 brz-text-lg-center brz-ls-lg-0"><br></p><p class="brz-fs-lg-46 brz-ff-yxliyccigeyavbkozixanwqgxarvhbfyywfq brz-ft-upload brz-fw-lg-300 brz-lh-lg-1_3 brz-text-lg-center brz-ls-lg-0"><br></p><p class="brz-fs-lg-46 brz-ff-yxliyccigeyavbkozixanwqgxarvhbfyywfq brz-ft-upload brz-fw-lg-300 brz-lh-lg-1_3 brz-text-lg-center brz-ls-lg-0"><br></p>';
        $this->assertFalse($element->hasAnyTagsInsidePTag($html));

        // Test case 3: p tag with no inner tags
        $html = "<p>This is a paragraph.</p>";
        $this->assertTrue($element->hasAnyTagsInsidePTag($html));

        // Test case 4: p tag with a tag that should be ignored
        $html = "<p>This is a <br> paragraph.</p>"; 
        $this->assertTrue($element->hasAnyTagsInsidePTag($html));

        // Test case 5: p tag with another p tag inside
        $html = "<p>This is a <p>paragraph.</p></p>"; 
        $this->assertTrue($element->hasAnyTagsInsidePTag($html));

        // Test case 6: p tag with other tags
        $html = "<p>This is a <b>paragraph.</b></p>"; 
        $this->assertTrue($element->hasAnyTagsInsidePTag($html));

        $html = "<p>This is a <b>paragraph.</b><br></p>";
        $this->assertTrue($element->hasAnyTagsInsidePTag($html));

        $html = "<p><br>This is a <b>paragraph.</b><br> <p>paragraph.<br></p></p>";
        $this->assertTrue($element->hasAnyTagsInsidePTag($html));
    }
}

class ElementT extends Element
{

}
