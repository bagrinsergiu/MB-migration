/* eslint-disable quotes, no-irregular-whitespace */
import { getTabs } from "..";
import { Entry, Output } from "../../types/type";
import { beforeEach, describe, expect, test } from "@jest/globals";

const entry: Entry = {
  selector: "test",
  defaultFamily: "lato",
  families: {},
  urlMap: {}
};

interface Data {
  entry: Entry;
  output: Output;
  html: string;
}

//#region Example 1 (Button with Icon)
const ex1: Data = {
  html: `<article id="tabs-4541990" class="site-section subpalette4 tabs editable tabs-layout  last" data-id="4541990" data-category="tabs" style="padding-top: 77.796875px !important;padding-bottom: 77.796875px !important;"><div class="bg-helper"><div class="bg-opacity" style="opacity: 0.0"></div></div><div class="content-wrapper clearfix"><div class="tabs-container"><div class="tabs-list-container"><div class="tabs-list" role="tablist"><button class="tab-title" data-tab-id="20051635" data-section="4541990" id="tabs-20051635-title" role="tab" aria-controls="tabs-20051635-body" aria-selected="true" tabindex="0"><div class="text-content text-0 editable stop-propagation-while-editing" data-id="20051637" data-category="text"><div><p><a href="/get-involved" data-location="existing" data-button="false" data-detail="492163" data-category="link" target="_self" class="cloverlinks" style="font-size: 1.0081em; color: rgb(167, 207, 93);">START YOUR RELATIONSHIP WITH JESUS CHRIST</a></p></div></div></button><button class="tab-title" data-tab-id="19962881" data-section="4541990" id="tabs-19962881-title" role="tab" aria-controls="tabs-19962881-body" aria-selected="false" tabindex="-1"><div class="text-content text-0 editable stop-propagation-while-editing" data-id="19962895" data-category="text"><div><p><a href="/giving" data-location="existing" data-button="false" data-detail="492164" data-category="link" target="_self" class="cloverlinks" style="color: rgb(167, 207, 93);">GIVE ONLINE</a></p></div></div></button>   <button class="tab-title" data-tab-id="19962879" data-section="4541990" id="tabs-19962879-title" role="tab" aria-controls="tabs-19962879-body" aria-selected="false" tabindex="-1"><div class="text-content text-0 editable stop-propagation-while-editing" data-id="19962889" data-category="text"><div><p style="color: rgb(187, 232, 105);"><a href="https://bit.ly/CCN-Facebook-Group-Join" data-location="external" data-button="false" data-detail="https://bit.ly/CCN-Facebook-Group-Join" data-category="link" target="_blank" class="cloverlinks" style="color: rgb(167, 207, 93);">w</a>atch live - facebook</p></div></div></button><button class="tab-title" data-tab-id="23996651" data-section="4541990" id="tabs-23996651-title" role="tab" aria-controls="tabs-23996651-body" aria-selected="false" tabindex="-1"><div class="text-content text-0 editable stop-propagation-while-editing" data-id="23996653" data-category="text"><div><p><a href="https://www.youtube.com/user/christcenteredchurc1/videos" data-location="external" data-button="false" data-detail="https://www.youtube.com/user/christcenteredchurc1/videos" data-category="link" target="_blank" class="cloverlinks">WATCH LIVE - YOUTUBE</a></p></div></div></button>     <button class="tab-title" data-tab-id="23996658" data-section="4541990" id="tabs-23996658-title" role="tab" aria-controls="tabs-23996658-body" aria-selected="false" tabindex="-1"><div class="text-content text-0 editable stop-propagation-while-editing" data-id="23996660" data-category="text"><div><p><a href="/lccn" data-location="existing" data-button="false" data-detail="1116658" data-category="link" target="_self" class="cloverlinks">LCCN (WOMEN'S MINISTRY)</a></p></div></div></button> <button class="tab-title" data-tab-id="23996663" data-section="4541990" id="tabs-23996663-title" role="tab" aria-controls="tabs-23996663-body" aria-selected="false" tabindex="-1"><div class="text-content text-0 editable stop-propagation-while-editing" data-id="23996665" data-category="text"><div><p><a href="/events" data-location="existing" data-button="false" data-detail="504671" data-category="link" target="_self" class="cloverlinks">EVENTS</a></p></div></div></button></div></div>  <div class="tab-body-container">   <div class="text-content text-1 tab-body editable" data-id="20051636" data-category="text" id="tabs-20051635-body" role="tabpanel" aria-labelledby="tabs-20051635-title" tabindex="0"><div><p style="font-size: 1.0776em; letter-spacing: 2.2272px; line-height: 37.500477px;">We invite you to worship with us online via Facebook or YouTube!</p><p style="font-size: 0.8082em; letter-spacing: 2.2272px; line-height: 1em;"><br></p><p style="font-size: 0.8082em; letter-spacing: 2.2272px;"><font color="#f5efed" face="Roboto, Roboto Regular, sans-serif" style="font-size: 1em; letter-spacing: 0.12em;"><span style="font-size: 1.2em;" class="clovercustom">Sundays at 10:10 AM ET&nbsp; &nbsp;</span></font><a href="https://bit.ly/YouTubeWatch-CCN" data-location="external" data-button="false" data-detail="https://bit.ly/YouTubeWatch-CCN" data-category="link" target="_blank" class="socialIconLink cloverlinks" style="font-size: 1.1333em; letter-spacing: 0.12em;"><span data-socialicon="circleyoutube"><span class="socialIconSymbol" aria-hidden="true"></span><span class="sr-only">circleyoutube</span></span> </a><span style="font-size: 1.6497em;" class="clovercustom"><span class="clovercustom">&nbsp;</span><span class="clovercustom">&nbsp;</span></span><a href="https://www.facebook.com/christcenterednation" data-location="external" data-button="false" data-detail="https://www.facebook.com/christcenterednation" data-category="link" target="_blank" class="socialIconLink cloverlinks" style="font-size: 0.8082em; letter-spacing: 0.12em;"><span data-socialicon="circlefacebook"><span class="socialIconSymbol" style="font-size: 2.887em;" aria-hidden="true"></span><span class="sr-only">circlefacebook</span></span></a><span style="font-size: 1.2em;" class="clovercustom"> &nbsp;</span><br></p><p style="font-size: 0.8621em; font-style: italic; letter-spacing: 2.2272px; line-height: 2em;">Join our<span class="clovercustom">&nbsp;</span><a href="https://bit.ly/CCN-Facebook-Group-Join" data-location="external" data-button="false" data-detail="https://bit.ly/CCN-Facebook-Group-Join" data-category="link" target="_blank" class="cloverlinks" style="font-style: normal; letter-spacing: 0.01em; text-align: left; text-decoration: underline;">Facebook</a><span class="clovercustom">&nbsp;</span>Community<span style="font-size: 0.8621em; letter-spacing: 0.12em;" class="clovercustom">&nbsp;</span></p><p style="font-size: 0.8621em; font-style: italic; letter-spacing: 2.2272px; line-height: 2em;"><span style="letter-spacing: 0.01em; text-align: left;" class="clovercustom">Get service alerts, reminders and special announcements</span><span class="clovercustom">&nbsp;</span>TEXT "LOOP" to 404-900-7705</p><div><br></div></div></div> <div class="text-content text-1 tab-body editable" data-id="19962892" data-category="text" id="tabs-19962881-body" role="tabpanel" aria-labelledby="tabs-19962881-title" tabindex="0" hidden="hidden"><div><p style="font-size: 0.7543em; font-style: italic;">Christ Centered Church (Christ Centered Nation) operates virtually, is based in Atlanta, GA and is led by Senior Pastor, Tim Fryar of Tim Fryar Ministries. To opt out of text communication text 'Stop' to 404-900-7705 or email <a href="mailto:info@imchristcentered.org">info@imchristcentered.org</a> for help.</p></div></div> <div class="text-content text-1 tab-body editable" data-id="19962888" data-category="text" id="tabs-19962879-body" role="tabpanel" aria-labelledby="tabs-19962879-title" tabindex="0" hidden="hidden"><div><p style="font-size: 1.0776em; letter-spacing: 2.2272px; line-height: 37.500477px;">We invite you to worship with us online via Facebook or YouTube!</p><p style="font-size: 0.8082em; letter-spacing: 2.2272px; line-height: 1em;"><br></p><p style="font-size: 0.8082em; letter-spacing: 2.2272px;"><font color="#f5efed" face="Roboto, Roboto Regular, sans-serif" style="font-size: 1em; letter-spacing: 0.12em;"><span style="font-size: 1.2em;" class="clovercustom">Sundays at 10:10 AM ET&nbsp; &nbsp;</span></font><a href="https://bit.ly/YouTubeWatch-CCN" data-location="external" data-button="false" data-detail="https://bit.ly/YouTubeWatch-CCN" data-category="link" target="_blank" class="socialIconLink cloverlinks" style="font-size: 1.1333em; letter-spacing: 0.12em;"><span data-socialicon="circleyoutube"><span class="socialIconSymbol" aria-hidden="true"></span><span class="sr-only">circleyoutube</span></span> </a><span style="font-size: 1.6497em;" class="clovercustom"><span class="clovercustom">&nbsp;</span><span class="clovercustom">&nbsp;</span></span><a href="https://www.facebook.com/christcenterednation" data-location="external" data-button="false" data-detail="https://www.facebook.com/christcenterednation" data-category="link" target="_blank" class="socialIconLink cloverlinks" style="font-size: 0.8082em; letter-spacing: 0.12em;"><span data-socialicon="circlefacebook"><span class="socialIconSymbol" style="font-size: 2.887em;" aria-hidden="true"></span><span class="sr-only">circlefacebook</span></span></a><span style="font-size: 1.2em;" class="clovercustom"> &nbsp;</span><br></p><p style="font-size: 0.8621em; font-style: italic; letter-spacing: 2.2272px; line-height: 2em;">Join our<span class="clovercustom">&nbsp;</span><a href="https://bit.ly/CCN-Facebook-Group-Join" data-location="external" data-button="false" data-detail="https://bit.ly/CCN-Facebook-Group-Join" data-category="link" target="_blank" class="cloverlinks" style="font-style: normal; letter-spacing: 0.01em; text-align: left; text-decoration: underline;">Facebook</a><span class="clovercustom">&nbsp;</span>Community<span style="font-size: 0.8621em; letter-spacing: 0.12em;" class="clovercustom">&nbsp;</span></p><p style="font-size: 0.8621em; font-style: italic; letter-spacing: 2.2272px; line-height: 2em;"><span style="letter-spacing: 0.01em; text-align: left;" class="clovercustom">Get service alerts, reminders and special announcements</span><span class="clovercustom">&nbsp;</span>TEXT "LOOP" to 404-900-7705</p><div><br></div></div></div><div class="text-content text-1 tab-body editable" data-id="23996652" data-category="text" id="tabs-23996651-body" role="tabpanel" aria-labelledby="tabs-23996651-title" tabindex="0" hidden="hidden"><div><p style="font-size: 1.0776em; letter-spacing: 2.2272px; line-height: 37.500477px;">We invite you to worship with us online via Facebook or YouTube!</p><p style="font-size: 0.8082em; letter-spacing: 2.2272px; line-height: 1em;"><br></p><p style="font-size: 0.8082em; letter-spacing: 2.2272px;"><font color="#f5efed" face="Roboto, Roboto Regular, sans-serif" style="font-size: 1em; letter-spacing: 0.12em;"><span style="font-size: 1.2em;" class="clovercustom">Sundays at 10:10 AM ET&nbsp; &nbsp;</span></font><a href="https://bit.ly/YouTubeWatch-CCN" data-location="external" data-button="false" data-detail="https://bit.ly/YouTubeWatch-CCN" data-category="link" target="_blank" class="socialIconLink cloverlinks" style="font-size: 1.1333em; letter-spacing: 0.12em;"><span data-socialicon="circleyoutube"><span class="socialIconSymbol" aria-hidden="true"></span><span class="sr-only">circleyoutube</span></span> </a><span style="font-size: 1.6497em;" class="clovercustom"><span class="clovercustom">&nbsp;</span><span class="clovercustom">&nbsp;</span></span><a href="https://www.facebook.com/christcenterednation" data-location="external" data-button="false" data-detail="https://www.facebook.com/christcenterednation" data-category="link" target="_blank" class="socialIconLink cloverlinks" style="font-size: 0.8082em; letter-spacing: 0.12em;"><span data-socialicon="circlefacebook"><span class="socialIconSymbol" style="font-size: 2.887em;" aria-hidden="true"></span><span class="sr-only">circlefacebook</span></span></a><span style="font-size: 1.2em;" class="clovercustom"> &nbsp;</span><br></p><p style="font-size: 0.8621em; font-style: italic; letter-spacing: 2.2272px; line-height: 2em;">Join our<span class="clovercustom">&nbsp;</span><a href="https://bit.ly/CCN-Facebook-Group-Join" data-location="external" data-button="false" data-detail="https://bit.ly/CCN-Facebook-Group-Join" data-category="link" target="_blank" class="cloverlinks" style="font-style: normal; letter-spacing: 0.01em; text-align: left; text-decoration: underline;">Facebook</a><span class="clovercustom">&nbsp;</span>Community<span style="font-size: 0.8621em; letter-spacing: 0.12em;" class="clovercustom">&nbsp;</span></p><p style="font-size: 0.8621em; font-style: italic; letter-spacing: 2.2272px; line-height: 2em;"><span style="letter-spacing: 0.01em; text-align: left;" class="clovercustom">Get service alerts, reminders and special announcements</span><span class="clovercustom">&nbsp;</span>TEXT "LOOP" to 404-900-7705</p></div></div><div class="text-content text-1 tab-body editable" data-id="23996659" data-category="text" id="tabs-23996658-body" role="tabpanel" aria-labelledby="tabs-23996658-title" tabindex="0" hidden="hidden"><div><p class="finaldraft_placeholder">This temporary filler text disappears once you click it. This is just placeholder example text that can be deleted. Type your own unique, authentic, and appropriate text for this tab here.</p></div></div> <div class="text-content text-1 tab-body editable" data-id="23996664" data-category="text" id="tabs-23996663-body" role="tabpanel" aria-labelledby="tabs-23996663-title" tabindex="0" hidden="hidden"><div><p class="finaldraft_placeholder">This temporary filler text disappears once you click it. This is just placeholder example text that can be deleted. Type your own unique, authentic, and appropriate text for this tab here.</p></div></div></div></div></div></article>`,
  // prettier-ignore
  entry: {...entry, selector: "[data-id=\"4541990\"]"},
  output: {
    data: {
      borderColorHex: "#000000",
      borderColorOpacity: 1,
      bgColorHex: "#ffffff",
      bgColorOpacity: 1,
      borderWidth: NaN,
      colorHex: "#000000",
      colorOpacity: 1,
      fontFamily: "lato",
      fontFamilyType: "upload",
      fontSize: NaN,
      fontStyle: "",
      fontWeight: NaN,
      letterSpacing: 0,
      lineHeight: 1.3,
      mobileLineHeight: 1.2,
      tabletLineHeight: 1.2,
      navStyle: "style-3",
      uppercase: false,
      contentBgColorHex: "#ffffff",
      contentBgColorOpacity: 1
    }
  }
};
//#endregion

//#region Example 2 (Tab with background)
const ex2: Data = {
  html: `<article id="tabs-5505962" class="site-section subpalette1 tabs editable tabs-layout  last" data-id="5505962" data-category="tabs">
  <div class="bg-helper">
    <div class="bg-opacity" style="opacity: 0.0"></div>
  </div
  <div class="content-wrapper clearfix">
  <header class="text-content text-1 title-text editable" data-id="25620641" data-category="text"><div><p>SMALL GROUP FAQ</p></div></header>
  <div class="text-content text-2 editable" data-id="25620642" data-category="text"><div><p><br></p></div></div>
<div class="tabs-container">
  <div class="tabs-list-container">
    <div class="tabs-list" role="tablist"><div class="tabs-ribbons"><div class="tabs-ribbon" style="height: 53px;"></div></div>
  <button class="tab-title" data-tab-id="25620638" data-section="5505962" id="tabs-25620638-title" role="tab" aria-controls="tabs-25620638-body" aria-selected="true" tabindex="0"><div class="text-content text-0 editable stop-propagation-while-editing" data-id="25620644" data-category="text"><div><p><span class="clovercustom" style="font-size: 16px; font-style: normal; font-weight: 400; letter-spacing: normal; text-align: start; text-decoration: none;">What is a Small Group?</span><br></p></div></div></button>
</div></div>  <div class="tab-body-container">
  <div class="text-content text-1 tab-body editable" data-id="25620643" data-category="text" id="tabs-25620638-body" role="tabpanel" aria-labelledby="tabs-25620638-title" tabindex="0"><div><p><span class="clovercustom" style="font-size: 16px;">Small Groups are groups of people who meet regularly for community and connection. Each Small Group decides the specifics of how, when, and for how long it will meet. Generally, groups will meet in someone’s home every other Sunday for about an hour and a half.&nbsp;</span><br></p></div></div>
  <div class="text-content text-1 tab-body editable" data-id="25620645" data-category="text" id="tabs-25620639-body" role="tabpanel" aria-labelledby="tabs-25620639-title" tabindex="0" hidden="hidden"><div><p><span class="clovercustom" style="font-size: 16px;">A Topical Group is specifically focused on one topic. The group commits to meeting regularly for one year for discussion. These are not classes, and there will not be a “teacher” but a facilitator to lead the discussion.&nbsp;</span><br></p></div></div>
  <div class="text-content text-1 tab-body editable" data-id="25620647" data-category="text" id="tabs-25620640-body" role="tabpanel" aria-labelledby="tabs-25620640-title" tabindex="0" hidden="hidden"><div><p><span class="clovercustom" style="font-size: 16px;">Small Groups have three parts: they share food together, talk about Jesus, and care for each other through prayer. Each group decides how to do this, whether it’s a dessert or a full meal. Whether it’s studying the Bible or reading a book together.</span><br></p></div></div>
  <div class="text-content text-1 tab-body editable" data-id="25620656" data-category="text" id="tabs-25620655-body" role="tabpanel" aria-labelledby="tabs-25620655-title" tabindex="0" hidden="hidden"><div><p><span class="clovercustom" style="font-size: 16px;">Each group looks a little different, but all share the same vision: connecting regularly with others in community to love and encourage each other.&nbsp;</span><br></p></div></div>
  <div class="text-content text-1 tab-body editable" data-id="25620659" data-category="text" id="tabs-25620658-body" role="tabpanel" aria-labelledby="tabs-25620658-title" tabindex="0" hidden="hidden"><div><p><span class="clovercustom" style="font-size: 16px;">Anyone, really: singles, couples, young, seasoned, and families. Each group has different roles: a facilitator who guides the discussion, a host (sometimes the same person as the facilitator) who helps create a hospitable environment, and participants who commit to growing in their love for Jesus and others together.&nbsp;</span><br></p></div></div>
  <div class="text-content text-1 tab-body editable" data-id="25620662" data-category="text" id="tabs-25620661-body" role="tabpanel" aria-labelledby="tabs-25620661-title" tabindex="0" hidden="hidden"><div><p><span class="clovercustom" style="font-size: 16px;">Click </span><a style="font-size: 16px; font-family: &quot;Proxima Nova&quot;, &quot;Proxima Nova Heavy&quot;, sans-serif; font-weight: 400;" href="https://www.elexiogiving.com/App/Form/d9a90067-1fb1-4b3a-b232-0171bd84c849" data-location="external" data-button="false" data-detail="https://www.elexiogiving.com/App/Form/d9a90067-1fb1-4b3a-b232-0171bd84c849" data-category="link" target="_blank" class="cloverlinks"><u>here</u></a><span class="clovercustom" style="font-size: 16px;"> to fill out our Small Group interest form, and someone will reach out to you soon to connect!</span><br></p></div></div>
  <div class="text-content text-1 tab-body editable" data-id="25620666" data-category="text" id="tabs-25620665-body" role="tabpanel" aria-labelledby="tabs-25620665-title" tabindex="0" hidden="hidden"><div><p><span class="clovercustom" style="font-size: 16px;">Small Groups have used different approaches for their kids. Some will arrange to hire babysitters, sometimes together, sometimes separately. Others have older kids entertain younger kids. Others have their kids engage in the discussion time. And still, others make it work with kids crawling in laps and across the floor!&nbsp;</span><br></p></div></div>
</div></div>
</div>
</article>
`,
  // prettier-ignore
  entry: {...entry, selector: "[data-id=\"5505962\"]"},
  output: {
    data: {
      bgColorHex: "#ffffff",
      bgColorOpacity: 1,
      borderColorHex: "#000000",
      borderColorOpacity: 1,
      borderWidth: NaN,
      colorHex: "#000000",
      colorOpacity: 1,
      contentBgColorHex: "#ffffff",
      contentBgColorOpacity: 1,
      fontFamily: "lato",
      fontFamilyType: "upload",
      fontSize: 16,
      fontStyle: "",
      fontWeight: 400,
      letterSpacing: 0,
      lineHeight: 1.3,
      mobileLineHeight: 1.2,
      navStyle: "style-3",
      tabletLineHeight: 1.2,
      uppercase: false
    }
  }
};
//#endregion

describe.each([ex1, ex2])(
  "testing 'getTabs' function nr %#",
  ({ entry, output, html }) => {
    beforeEach(() => {
      document.body.innerHTML = html;
    });

    test("expected", () => {
      expect(getTabs(entry)).toStrictEqual(output);
    });
  }
);

describe("testing 'getTabs' error function", () => {
  test.each<[Entry, Output]>([
    // Default
    [entry, { error: "Element with selector test not found" }]
  ])("getTabs nr %#", (entry, output) => {
    expect(getTabs(entry)).toStrictEqual(output);
  });
});
