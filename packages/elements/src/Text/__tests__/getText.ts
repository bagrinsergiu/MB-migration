/* eslint-disable quotes, no-irregular-whitespace */
import { getText } from "../";
import { Entry, Output } from "../../types/type";
import { beforeEach, describe, expect, test } from "@jest/globals";

const entry: Entry = {
  selector: "test",
  defaultFamily: "lato",
  families: {}
};

interface Data {
  entry: Entry;
  output: Output;
  html: string;
}

//#region Example 1 (Button with Icon)

const ex1: Data = {
  html: `<div class="text-content text-1 editable" data-id="141588" data-category="text"><div><p><br></p><p style="text-align: center; font-weight: 700; font-family: &quot;League Gothic&quot;, sans-serif; color: rgb(5, 3, 5);"><br></p><p style="text-align: center; font-weight: 400; font-family: &quot;League Gothic&quot;, sans-serif;"><a href="/about-us/who-we-are" class="sites-button cloverlinks" role="button" data-location="existing" data-detail="23432" data-category="button" target="_self" style="font-weight: 400; font-size: 0.8291em;"><span class="clovercustom" style="color: rgb(10, 9, 10); text-align: left; font-weight: 600; font-size: 1.6153em; font-family: Dosis, &quot;Dosis Regular&quot;, sans-serif;">I'm New</span><span data-socialicon="circlerightarrow"><span class="socialIconSymbol" aria-hidden="true"></span><span class="sr-only">circlerightarrow</span></span> </a> <br></p><p style="text-align: center; font-weight: 300;"><br></p><p style="text-align: center; font-weight: 300;"><br></p><p style="text-align: center; font-weight: 300;"><br></p></div></div>`,
  // prettier-ignore
  entry: { ...entry, selector: "[data-id=\"141588\"]" },
  output: {
    data: [
      {
        type: "Wrapper",
        value: {
          _id: "1",
          _styles: ["wrapper", "wrapper--richText"],
          items: [
            {
              type: "RichText",
              value: {
                _id: "1",
                _styles: ["richText"],
                //prettier-ignore
                "text":  "<p class=\"brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0\"><br></p><p class=\"brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0\"><br></p>"
              }
            }
          ]
        }
      },
      {
        type: "Cloneable",
        value: {
          _id: "1",
          _styles: ["wrapper-clone", "wrapper-clone--button"],
          horizontalAlign: undefined,
          items: [
            {
              type: "Button",
              value: {
                _id: "1",
                _styles: ["button"],
                text: "I'm New ",
                borderStyle: "none",
                iconName: "circle-right-37",
                linkExternal: "http://localhost/about-us/who-we-are",
                linkType: "external",
                linkExternalBlank: "off"
              }
            }
          ]
        }
      },
      {
        type: "Wrapper",
        value: {
          _id: "1",
          _styles: ["wrapper", "wrapper--richText"],
          items: [
            {
              type: "RichText",
              value: {
                _id: "1",
                _styles: ["richText"],
                //prettier-ignore
                "text": "<p class=\"brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0\"><br></p><p class=\"brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0\"><br></p><p class=\"brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0\"><br></p>"
              }
            }
          ]
        }
      }
    ]
  }
};

//#endregion

//#region Example 2 (Removed All Button + Icons inside [UL, OL])

const ex2: Data = {
  html: `<div class="text-content text-1 editable" data-id="15600705" data-category="text"><div><p>Please <span class="clovercustom" style="font-weight: 700;">Request Online Directory Account</span> to create a User Account to login into the Directory portal.&nbsp; &nbsp;Please insert your Name, Address, Phone and Email, so we can update our records.&nbsp; &nbsp;Please be patient with us, we are getting our records in order and it make take a couple days before you get an email with your login credentials.&nbsp;</p><p><br></p><p><span class="clovercustom" style="font-weight: 700;">Directory </span>will redirect to <span class="clovercustom" style="font-weight: 400;"><span class="clovercustom" style="font-weight: 700;">Shelby Next Membership</span> at <a href="https://fbcterry.shelbynextchms.com" data-location="external" data-detail="https://fbcterry.shelbynextchms.com" data-category="link" target="_blank" class="cloverlinks">https://fbcterry.shelbynextchms.com</a></span>.</p><p>Other ways to access <span class="clovercustom" style="font-weight: 700;">Shelby Next Membership</span> can be found at your Apple App Store or Google Play Store.&nbsp;<br></p><p>For mobile apps, change <span class="clovercustom" style="font-weight: 700;">domain</span>.shelbynextchms.com to <u><span class="clovercustom" style="font-weight: 700;">fbcterry</span>.shelbynextchms.com</u>.&nbsp;&nbsp;</p><ul>
<li>
<a href="https://apps.apple.com/us/app/shelbynext-membership/id996581065" class="cloverlinks" data-category="link" data-location="external" data-detail="https://apps.apple.com/us/app/shelbynext-membership/id996581065" target="_self">iPhone/iPad app for Shelby Next Membership</a> <span data-socialicon="roundedapple"><span class="socialIconSymbol" aria-hidden="true"></span><span class="sr-only">roundedapple</span></span> </li>
<li>
<a href="https://play.google.com/store/apps/details?id=com.ministrybrands.shelbynext" class="cloverlinks" data-category="link" data-location="external" data-detail="https://play.google.com/store/apps/details?id=com.ministrybrands.shelbynext" target="_self">Android app for Shelby Next Membership</a>  <span data-socialicon="roundedgoogleplay"><span class="socialIconSymbol" aria-hidden="true"></span><span class="sr-only">roundedgoogleplay</span></span> <p><br></p>
</li>
</ul><p><span class="clovercustom" style="font-size: 0.94em; letter-spacing: 0.01em; font-weight: 700;">Calendar </span>is our Church-wide calendar.<br></p><p><br></p><p><br></p></div></div>`,
  //prettier-ignore
  entry: {...entry, selector: "[data-id=\"15600705\"]"},
  output: {
    data: [
      {
        type: "Wrapper",
        value: {
          _id: "1",
          _styles: ["wrapper", "wrapper--richText"],
          items: [
            {
              type: "RichText",
              value: {
                _id: "1",
                _styles: ["richText"],
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><span>Please </span><span style="font-weight: 700; ">Request Online Directory Account</span><span> to create a User Account to login into the Directory portal.&nbsp; &nbsp;Please insert your Name, Address, Phone and Email, so we can update our records.&nbsp; &nbsp;Please be patient with us, we are getting our records in order and it make take a couple days before you get an email with your login credentials.&nbsp;</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 700; ">Directory </span><span>will redirect to </span><span style="font-weight: 400; "><span style="font-weight: 700; ">Shelby Next Membership</span> at <a href="https://fbcterry.shelbynextchms.com" data-location="external" data-detail="https://fbcterry.shelbynextchms.com" data-category="link" target="_blank"><span>https://fbcterry.shelbynextchms.com</span></a></span><span>.</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><span>Other ways to access </span><span style="font-weight: 700; ">Shelby Next Membership</span><span> can be found at your Apple App Store or Google Play Store.&nbsp;</span><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><span>For mobile apps, change </span><span style="font-weight: 700; ">domain</span><span>.shelbynextchms.com to </span><u><span style="font-weight: 700; ">fbcterry</span><span>.shelbynextchms.com</span></u><span>.&nbsp;&nbsp;</span></p><ul><li class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><a href="https://apps.apple.com/us/app/shelbynext-membership/id996581065" data-category="link" data-location="external" data-detail="https://apps.apple.com/us/app/shelbynext-membership/id996581065" target="_self"><span>iPhone/iPad app for Shelby Next Membership</span></a>  </li><li class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><a href="https://play.google.com/store/apps/details?id=com.ministrybrands.shelbynext" data-category="link" data-location="external" data-detail="https://play.google.com/store/apps/details?id=com.ministrybrands.shelbynext" target="_self"><span>Android app for Shelby Next Membership</span></a>   <p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><br></p></li></ul><p class="brz-fs-lg-0 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-0_0"><span style="font-weight: 700; ">Calendar </span><span>is our Church-wide calendar.</span><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><br></p>'
              }
            }
          ]
        }
      }
    ]
  }
};

//#endregion

//#region Example 3 (Footer With Social Icons)

const ex3: Data = {
  html: `<div class="text-content text-0 editable" data-id="141413" data-category="text"><div><p style="text-align: center;">Address: 404 Church St. Columbia, LA 71418</p><p style="text-align: center;">Phone Number: 318-649-2202</p><p style="text-align: center;">Fax Number: 318-649-2206</p><p style="text-align: center;">Email: fbcbeyond@bellsouth.net</p><p style="text-align: center; font-size: 1.0667em;"><br></p><p style="text-align: center; font-size: 1.0667em;"><span data-socialicon="circlefacebook"><span class="socialIconSymbol" aria-hidden="true"></span><span class="sr-only">circlefacebook</span></span>  <span data-socialicon="circletwitter"><span class="socialIconSymbol" aria-hidden="true"></span><span class="sr-only">circletwitter</span></span>  [social=circleundefined]<br></p></div></div>`,
  //prettier-ignore
  entry: {...entry, selector: "[data-id=\"141413\"]"},
  output: {
    data: [
      {
        type: "Wrapper",
        value: {
          _id: "1",
          _styles: ["wrapper", "wrapper--richText"],
          items: [
            {
              type: "RichText",
              value: {
                _id: "1",
                _styles: ["richText"],
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0"><span>Address: 404 Church St. Columbia, LA 71418</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0"><span>Phone Number: 318-649-2202</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0"><span>Fax Number: 318-649-2206</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0"><span>Email: fbcbeyond@bellsouth.net</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0"><br></p>'
              }
            }
          ]
        }
      },
      {
        type: "Cloneable",
        value: {
          _id: "1",
          _styles: ["wrapper-clone", "wrapper-clone--icon"],
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                padding: 7,
                customSize: 26,
                name: "logo-facebook"
              }
            },
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                padding: 7,
                customSize: 26,
                name: "logo-twitter"
              }
            }
          ],
          horizontalAlign: "center"
        }
      },
      {
        type: "Wrapper",
        value: {
          _id: "1",
          _styles: ["wrapper", "wrapper--richText"],
          items: [
            {
              type: "RichText",
              value: {
                _id: "1",
                _styles: ["richText"],
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0">  <span>  [social=circleundefined]</span><br></p>'
              }
            }
          ]
        }
      }
    ]
  }
};

//#endregion

//#region Example 4 (FontStyle: italic)

const ex4: Data = {
  html: `<div class="text-content text-1 editable" data-id="141447" data-category="text"><div><p style="font-weight: 700; font-style: italic; color: rgb(237, 227, 225); text-align: center; font-size: 1.8495em;"> We are a church whose goal is to make followers of Jesus Christ.&nbsp;</p><p style="font-size: 1.4028em; font-weight: 700; font-style: italic; color: rgb(237, 227, 225); text-align: center;">We believe that Jesus Christ died on the cross for our sins,</p><p style="font-size: 1.4028em; font-weight: 700; font-style: italic; color: rgb(237, 227, 225); text-align: center;"> was buried, and on the third day rose from the dead!&nbsp;</p><p style="font-size: 1.4028em; font-weight: 700; font-style: italic; color: rgb(237, 227, 225); text-align: center;">We believe that everyone who turns from their sins and believes in Jesus Christ will be saved. How?&nbsp;</p><p style="font-size: 1.4028em; font-weight: 700; font-style: italic; color: rgb(237, 227, 225); text-align: center;">One calls on the name of Jesus for salvation!</p><p style="font-size: 1.4028em; font-weight: 700; font-style: italic; color: rgb(237, 227, 225); text-align: center;"><br></p><p style="font-weight: 700; font-style: italic; color: rgb(230, 230, 189); text-align: center; font-size: 1.5306em;"><span class="clovercustom" style="font-size: 1.0417em;">Our worship services here in Shady<span class="clovercustom" style="color: rgb(230, 230, 189);"> Side, Maryla</span>nd are 10–11am each Sunday morning</span>.</p><p style="font-size: 1.4028em; font-weight: 700; font-style: italic; color: rgb(237, 227, 225); text-align: center;">During this time, we will read Scripture, pray together, sing worship songs,&nbsp;</p><p style="font-size: 1.4028em; font-weight: 700; font-style: italic; color: rgb(237, 227, 225); text-align: center;">and listen to the Bible preached.</p><p style="font-size: 1.4028em; font-weight: 700; font-style: italic; color: rgb(237, 227, 225); text-align: center;"><br></p><p style="font-size: 1.4028em; font-weight: 700; font-style: italic; color: rgb(237, 227, 225); text-align: center;">We hope you will come and feel welcome as you listen to God’s word to us in the Bible.&nbsp;</p><p style="font-size: 1.4028em; font-weight: 700; font-style: italic; color: rgb(237, 227, 225); text-align: center;">Our Pastor and Elders are here for you if you have any questions about our church, baptism, or</p><p style="font-size: 1.4028em; font-weight: 700; font-style: italic; color: rgb(237, 227, 225); text-align: center;">following Jesus. Our atmosphere is casual, so do not feel like you need to dress up to attend.&nbsp;</p><p style="font-size: 1.4028em; font-weight: 700; font-style: italic; color: rgb(237, 227, 225); text-align: center;">We hope you will browse our website to find out more about our church,&nbsp;</p><p style="font-size: 1.4028em; font-weight: 700; font-style: italic; color: rgb(237, 227, 225); text-align: center;">our Baptist beliefs, and our ministries.</p><p><em style="color: red">Test</em></p><p style="font-size: 1.4028em;">&nbsp;</p></div></div>`,
  //prettier-ignore
  entry: {...entry, selector: "[data-id=\"141447\"]"},
  output: {
    data: [
      {
        type: "Wrapper",
        value: {
          _id: "1",
          _styles: ["wrapper", "wrapper--richText"],
          items: [
            {
              type: "RichText",
              value: {
                _id: "1",
                _styles: ["richText"],
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; "> We are a church whose goal is to make followers of Jesus Christ.&nbsp;</em></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">We believe that Jesus Christ died on the cross for our sins,</em></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; "> was buried, and on the third day rose from the dead!&nbsp;</em></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">We believe that everyone who turns from their sins and believes in Jesus Christ will be saved. How?&nbsp;</em></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">One calls on the name of Jesus for salvation!</em></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0"><span style="color: rgb(230, 230, 189); font-weight: 700; ">Our worship services here in Shady<span style="color: rgb(230, 230, 189); font-weight: 700; "> Side, Maryla</span>nd are 10–11am each Sunday morning</span><em style="color: rgb(230, 230, 189); font-weight: 700; ">.</em></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">During this time, we will read Scripture, pray together, sing worship songs,&nbsp;</em></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">and listen to the Bible preached.</em></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">We hope you will come and feel welcome as you listen to God’s word to us in the Bible.&nbsp;</em></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">Our Pastor and Elders are here for you if you have any questions about our church, baptism, or</em></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">following Jesus. Our atmosphere is casual, so do not feel like you need to dress up to attend.&nbsp;</em></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">We hope you will browse our website to find out more about our church,&nbsp;</em></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">our Baptist beliefs, and our ministries.</em></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><em style="color: red; ">Test</em></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0">&nbsp;</p>'
              }
            }
          ]
        }
      }
    ]
  }
};

//#endregion

//#region Example 5 (Remove empty content)

const ex5: Data = {
  html: `<div class="text-content text-1 editable" data-id="142241" data-category="text"><div><p data-uniq-id="aCG_1" data-generated-css="brz-css-kcw7Q" style="font-size: 15px; letter-spacing: -0.1px; line-height: 1.4;"><span class="clovercustom" style="font-size: inherit; font-weight: inherit; letter-spacing: inherit;"><u>Our approach </u>to design is based on the art of combining style and functionality. We strive to create spaces that are not only visually impressive, <u>but also serve as comfortable</u> and practical living environments.</span></p><p data-uniq-id="aCG_1" data-generated-css="brz-css-kcw7Q" style="font-size: 15px; letter-spacing: -0.1px; line-height: 1.4;"><br></p><p data-uniq-id="aCG_1" data-generated-css="brz-css-kcw7Q" style="font-size: 15px; letter-spacing: -0.1px; line-height: 1.4;"><span data-socialicon="email"><span class="socialIconSymbol" aria-hidden="true"></span><span class="sr-only">email</span></span><br></p><p><br></p><p data-uniq-id="sSLWq" data-generated-css="brz-css-hYqTF" style="font-size: 15px; font-weight: 600; letter-spacing: -0.1px; line-height: 1.4;"><span class="clovercustom" style="font-size: inherit; font-weight: inherit; letter-spacing: inherit;"><u>Be inspired by our designs where modern lines blend with classic elements to create harmony and coziness. We play with colors, textures and light to ensure that every corner of your home is filled with warmth and style.</u></span></p><p data-uniq-id="sSLWq" data-generated-css="brz-css-hYqTF" style="font-size: 15px; font-weight: 600; letter-spacing: -0.1px; line-height: 1.4;"><span class="clovercustom" style="font-size: inherit; font-weight: inherit; letter-spacing: inherit;"><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" style="font-size: 19px; letter-spacing: -0.1px; line-height: 1.3; text-align: right; color: rgb(235, 9, 9);"><span class="clovercustom" style="font-size: inherit; font-weight: inherit; letter-spacing: inherit;">Our team of talented designers and architects are ready to make your dreams come true.&nbsp;</span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" style="font-size: 19px; letter-spacing: -0.1px; line-height: 1.3;"><span class="clovercustom" style="font-size: inherit; font-weight: inherit; letter-spacing: inherit;"><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" style="letter-spacing: -0.1px; line-height: 1.3; text-align: center; color: rgb(242, 3, 55); font-size: 2.5196em;"><span class="clovercustom" style="font-weight: inherit; letter-spacing: inherit;"><span class="clovercustom" style="letter-spacing: -0.1px;">Our team of talented designers and architects are ready to make your dreams come true.&nbsp;</span><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" style="font-size: 19px; letter-spacing: -0.1px; line-height: 1.3; text-align: center;"><span class="clovercustom" style="font-size: inherit; font-weight: inherit; letter-spacing: inherit;"><span class="clovercustom" style="letter-spacing: -0.1px;"><br></span></span></p><ul style="font-size: medium; letter-spacing: normal;"><li data-uniq-id="vteAp" data-generated-css="brz-css-hf4DG" style="font-size: 14px; letter-spacing: -0.1px; line-height: 1.4; text-align: left; color: rgb(70, 242, 12);"><span class="clovercustom" style="font-size: inherit; font-weight: inherit; letter-spacing: inherit;">We approach each project carefully, taking into account your preferences, budget and individual space features.</span></li><li data-uniq-id="vteAp" data-generated-css="brz-css-hf4DG" style="font-size: 14px; letter-spacing: -0.1px; line-height: 1.4; text-align: left; color: rgb(222, 95, 22);">We approach each project carefully, taking into account your preferences, budget and individual space features.</li></ul><p><a href="http://google.com" class="sites-button cloverlinks" role="button" data-location="external" data-detail="http://google.com" data-category="button" target="_blank"><span class="clovercustom" style="text-align: center;">LINK</span></a><span class="clovercustom" style="text-align: center;"> </span></p></div></div>`,
  //prettier-ignore
  entry: {...entry, selector: "[data-id=\"142241\"]"},
  output: {
    data: [
      {
        type: "Wrapper",
        value: {
          _id: "1",
          _styles: ["wrapper", "wrapper--richText"],
          items: [
            {
              type: "RichText",
              value: {
                _id: "1",
                _styles: ["richText"],
                text: '<p data-uniq-id="aCG_1" data-generated-css="brz-css-kcw7Q" class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: inherit; "><u><span>Our approach </span></u>to design is based on the art of combining style and functionality. We strive to create spaces that are not only visually impressive, <u><span>but also serve as comfortable</span></u> and practical living environments.</span></p><p data-uniq-id="aCG_1" data-generated-css="brz-css-kcw7Q" class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-m_0_1"><br></p>'
              }
            }
          ]
        }
      },
      {
        type: "Cloneable",
        value: {
          _id: "1",
          _styles: ["wrapper-clone", "wrapper-clone--icon"],
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                padding: 7,
                customSize: 26,
                name: "email-85"
              }
            }
          ],
          horizontalAlign: undefined
        }
      },
      {
        type: "Wrapper",
        value: {
          _id: "1",
          _styles: ["wrapper", "wrapper--richText"],
          items: [
            {
              type: "RichText",
              value: {
                _id: "1",
                _styles: ["richText"],
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p data-uniq-id="sSLWq" data-generated-css="brz-css-hYqTF" class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><u><span>Be inspired by our designs where modern lines blend with classic elements to create harmony and coziness. We play with colors, textures and light to ensure that every corner of your home is filled with warmth and style.</span></u></span></p><p data-uniq-id="sSLWq" data-generated-css="brz-css-hYqTF" class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-0_0 brz-text-lg-right brz-ls-lg-NaN_0"><span style="font-weight: inherit; color: rgb(235, 9, 9); ">Our team of talented designers and architects are ready to make your dreams come true.&nbsp;</span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-2 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><span style="font-weight: inherit; ">Our team of talented designers and architects are ready to make your dreams come true.&nbsp;</span><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-0_0 brz-text-lg-center brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><span style=""><br></span></span></p><ul><li data-uniq-id="vteAp" data-generated-css="brz-css-hf4DG" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: inherit; color: rgb(70, 242, 12); ">We approach each project carefully, taking into account your preferences, budget and individual space features.</span></li><li data-uniq-id="vteAp" data-generated-css="brz-css-hf4DG" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-m_0_1"><span style="color: rgb(222, 95, 22); ">We approach each project carefully, taking into account your preferences, budget and individual space features.</span></li></ul>'
              }
            }
          ]
        }
      },
      {
        type: "Cloneable",
        value: {
          _id: "1",
          _styles: ["wrapper-clone", "wrapper-clone--button"],
          items: [
            {
              type: "Button",
              value: {
                _id: "1",
                _styles: ["button"],
                text: "LINK",
                borderStyle: "none",
                linkExternal: "http://google.com/",
                linkType: "external",
                linkExternalBlank: "on"
              }
            }
          ],
          horizontalAlign: undefined
        }
      }
    ]
  }
};

//#endregion

//#region Example 6 (Remove empty content v1)
const ex6: Data = {
  html: `<div class="text-content text-1 editable" data-id="142239" data-category="text"><div><p data-uniq-id="aCG_1" data-generated-css="brz-css-kcw7Q" style="font-size: 15px; letter-spacing: -0.1px; line-height: 1.4;"><span class="clovercustom" style="font-size: inherit; font-weight: inherit; letter-spacing: inherit;"><u>Our approach </u>to design is based on the art of combining style and functionality. We strive to create spaces that are not only visually impressive, <u>but also serve as comfortable</u> and practical living environments.</span></p><p data-uniq-id="aCG_1" data-generated-css="brz-css-kcw7Q" style="font-size: 15px; letter-spacing: -0.1px; line-height: 1.4;"><span class="clovercustom" style="font-size: inherit; font-weight: inherit; letter-spacing: inherit;"><span data-socialicon="apple"><span class="socialIconSymbol" aria-hidden="true"></span><span class="sr-only">apple</span></span> </span></p><p><br></p><p data-uniq-id="sSLWq" data-generated-css="brz-css-hYqTF" style="font-size: 15px; font-weight: 600; letter-spacing: -0.1px; line-height: 1.4;"><span class="clovercustom" style="font-size: inherit; font-weight: inherit; letter-spacing: inherit;">Be inspired by our designs where modern lines blend with classic elements to create harmony and coziness. We play with colors, textures and light to ensure that every corner of your home is filled with warmth and style.</span></p><p data-uniq-id="sSLWq" data-generated-css="brz-css-hYqTF" style="font-size: 15px; font-weight: 600; letter-spacing: -0.1px; line-height: 1.4;"><span class="clovercustom" style="font-size: inherit; font-weight: inherit; letter-spacing: inherit;"><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" style="font-size: 19px; letter-spacing: -0.1px; line-height: 1.3; text-align: right; color: rgb(9, 51, 237);"><span class="clovercustom" style="font-size: inherit; font-weight: inherit; letter-spacing: inherit;">Our team of talented designers and architects are ready to make your dreams come true.&nbsp;</span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" style="font-size: 19px; letter-spacing: -0.1px; line-height: 1.3;"><span class="clovercustom" style="font-size: inherit; font-weight: inherit; letter-spacing: inherit;"><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" style="font-size: 19px; letter-spacing: -0.1px; line-height: 1.3; text-align: center; color: rgb(242, 3, 55);"><span class="clovercustom" style="font-size: inherit; font-weight: inherit; letter-spacing: inherit;"><span class="clovercustom" style="letter-spacing: -0.1px;">Our team of talented designers and architects are ready to make your dreams come true.&nbsp;</span><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" style="font-size: 19px; letter-spacing: -0.1px; line-height: 1.3; text-align: center;"><span class="clovercustom" style="font-size: inherit; font-weight: inherit; letter-spacing: inherit;"><span class="clovercustom" style="letter-spacing: -0.1px;"><br></span></span></p><ul style="font-size: medium; letter-spacing: normal;"><li data-uniq-id="vteAp" data-generated-css="brz-css-hf4DG" style="font-size: 14px; letter-spacing: -0.1px; line-height: 1.4; text-align: left;"><span class="clovercustom" style="font-size: inherit; font-weight: inherit; letter-spacing: inherit;">We approach each project carefully, taking into account your preferences,<span class="clovercustom">&nbsp;</span><span class="clovercustom">budget and individual space features.</span></span></li><li data-uniq-id="vteAp" data-generated-css="brz-css-hf4DG" style="font-size: 14px; letter-spacing: -0.1px; line-height: 1.4; text-align: left; color: rgb(22, 222, 38);">We approach each project carefully, taking into account your preferences, budget and individual space features.</li></ul><p><a href="http://google.com" class="sites-button cloverlinks" role="button" data-location="external" data-detail="http://google.com" data-category="button" target="_blank"><span class="clovercustom" style="text-align: center;">google</span></a><span class="clovercustom" style="text-align: center;"> </span></p></div></div>`,
  //prettier-ignore
  entry: {...entry, selector: "[data-id=\"142239\"]"},
  output: {
    data: [
      {
        type: "Wrapper",
        value: {
          _id: "1",
          _styles: ["wrapper", "wrapper--richText"],
          items: [
            {
              type: "RichText",
              value: {
                _id: "1",
                _styles: ["richText"],
                text: '<p data-uniq-id="aCG_1" data-generated-css="brz-css-kcw7Q" class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: inherit; "><u><span>Our approach </span></u>to design is based on the art of combining style and functionality. We strive to create spaces that are not only visually impressive, <u><span>but also serve as comfortable</span></u> and practical living environments.</span></p>'
              }
            }
          ]
        }
      },
      {
        type: "Cloneable",
        value: {
          _id: "1",
          _styles: ["wrapper-clone", "wrapper-clone--icon"],
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                customSize: 26,
                name: "apple",
                padding: 7
              }
            }
          ],
          horizontalAlign: undefined
        }
      },
      {
        type: "Wrapper",
        value: {
          _id: "1",
          _styles: ["wrapper", "wrapper--richText"],
          items: [
            {
              type: "RichText",
              value: {
                _id: "1",
                _styles: ["richText"],
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p data-uniq-id="sSLWq" data-generated-css="brz-css-hYqTF" class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: inherit; ">Be inspired by our designs where modern lines blend with classic elements to create harmony and coziness. We play with colors, textures and light to ensure that every corner of your home is filled with warmth and style.</span></p><p data-uniq-id="sSLWq" data-generated-css="brz-css-hYqTF" class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-0_0 brz-text-lg-right brz-ls-lg-NaN_0"><span style="font-weight: inherit; color: rgb(9, 51, 237); ">Our team of talented designers and architects are ready to make your dreams come true.&nbsp;</span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-0_0 brz-text-lg-center brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><span style="font-weight: inherit; ">Our team of talented designers and architects are ready to make your dreams come true.&nbsp;</span><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-0_0 brz-text-lg-center brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><span style=""><br></span></span></p><ul><li data-uniq-id="vteAp" data-generated-css="brz-css-hf4DG" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: inherit; ">We approach each project carefully, taking into account your preferences,<span>&nbsp;</span><span style="font-weight: inherit; ">budget and individual space features.</span></span></li><li data-uniq-id="vteAp" data-generated-css="brz-css-hf4DG" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-m_0_1"><span style="color: rgb(22, 222, 38); ">We approach each project carefully, taking into account your preferences, budget and individual space features.</span></li></ul>'
              }
            }
          ]
        }
      },
      {
        type: "Cloneable",
        value: {
          _id: "1",
          _styles: ["wrapper-clone", "wrapper-clone--button"],
          items: [
            {
              type: "Button",
              value: {
                _id: "1",
                _styles: ["button"],
                text: "google",
                borderStyle: "none",
                linkExternal: "http://google.com/",
                linkType: "external",
                linkExternalBlank: "on"
              }
            }
          ],
          horizontalAlign: undefined
        }
      }
    ]
  }
};
//#endregion

//#region Example 7 (Remove empty content v2)

const ex7: Data = {
  html: `<header class="text-content text-0 title-text editable" data-id="141416" data-category="text"><div><p style="font-size: 1.7389em;">Register Here!&nbsp;<br><span data-icon="running"><span class="clovericons fas" aria-hidden="true"></span><span class="sr-only">Running</span></span> </p><p>wwww</p></div></header>`,
  //prettier-ignore
  entry: {...entry, selector: "[data-id=\"141416\"]"},
  output: {
    data: [
      {
        type: "Wrapper",
        value: {
          _id: "1",
          _styles: ["wrapper", "wrapper--richText"],
          items: [
            {
              type: "RichText",
              value: {
                _id: "1",
                _styles: ["richText"],
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><span>Register Here!&nbsp;</span><br> </p>'
              }
            }
          ]
        }
      },
      {
        type: "Cloneable",
        value: {
          _id: "1",
          _styles: ["wrapper-clone", "wrapper-clone--icon"],
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                padding: 7,
                customSize: 26,
                name: "user-run"
              }
            }
          ],
          horizontalAlign: undefined
        }
      },
      {
        type: "Wrapper",
        value: {
          _id: "1",
          _styles: ["wrapper", "wrapper--richText"],
          items: [
            {
              type: "RichText",
              value: {
                _id: "1",
                _styles: ["richText"],
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><span>wwww</span></p>'
              }
            }
          ]
        }
      }
    ]
  }
};
//#endregion

//#region Example 8 (Icon Text Icon)

const ex8: Data = {
  html: `<div class="text-content text-0 editable" data-id="141422" data-category="text"><div><p style="text-align: center;">Address: 404 Church St. Columbia, LA 71418</p><p style="text-align: center;">Phone Number: 318-649-2202</p><p style="text-align: center;">Fax Number: 318-649-2206</p><p style="text-align: center;">Email: fbcbeyond@bellsouth.net</p><p style="text-align: center; font-size: 1.0667em;"><br></p><p style="text-align: center; font-size: 1.0667em;"><span data-socialicon="circlefacebook"><span class="socialIconSymbol" aria-hidden="true"></span><span class="sr-only">circlefacebook</span></span>  <span data-socialicon="circletwitter"><span class="socialIconSymbol" aria-hidden="true"></span><span class="sr-only">circletwitter</span></span>  [social=circleundefined]</p><p style="text-align: center; font-size: 1.0667em;"><span data-socialicon="circlefacebook"><span class="socialIconSymbol" aria-hidden="true"></span><span class="sr-only">circlefacebook</span></span>  <span data-socialicon="circletwitter"><span class="socialIconSymbol" aria-hidden="true"></span><span class="sr-only">circletwitter</span></span>  [social=circleundefined]</p></div></div>`,
  //prettier-ignore
  entry: {...entry, selector: "[data-id=\"141422\"]"},
  output: {
    data: [
      {
        type: "Wrapper",
        value: {
          _id: "1",
          _styles: ["wrapper", "wrapper--richText"],
          items: [
            {
              type: "RichText",
              value: {
                _id: "1",
                _styles: ["richText"],
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0"><span>Address: 404 Church St. Columbia, LA 71418</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0"><span>Phone Number: 318-649-2202</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0"><span>Fax Number: 318-649-2206</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0"><span>Email: fbcbeyond@bellsouth.net</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0"><br></p>'
              }
            }
          ]
        }
      },
      {
        type: "Cloneable",
        value: {
          _id: "1",
          _styles: ["wrapper-clone", "wrapper-clone--icon"],
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                padding: 7,
                customSize: 26,
                name: "logo-facebook"
              }
            },
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                padding: 7,
                customSize: 26,
                name: "logo-twitter"
              }
            }
          ],
          horizontalAlign: "center"
        }
      },
      {
        type: "Wrapper",
        value: {
          _id: "1",
          _styles: ["wrapper", "wrapper--richText"],
          items: [
            {
              type: "RichText",
              value: {
                _id: "1",
                _styles: ["richText"],
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0">  <span>  [social=circleundefined]</span></p>'
              }
            }
          ]
        }
      },
      {
        type: "Cloneable",
        value: {
          _id: "1",
          _styles: ["wrapper-clone", "wrapper-clone--icon"],
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                padding: 7,
                customSize: 26,
                name: "logo-facebook"
              }
            },
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                padding: 7,
                customSize: 26,
                name: "logo-twitter"
              }
            }
          ],
          horizontalAlign: "center"
        }
      },
      {
        type: "Wrapper",
        value: {
          _id: "1",
          _styles: ["wrapper", "wrapper--richText"],
          items: [
            {
              type: "RichText",
              value: {
                _id: "1",
                _styles: ["richText"],
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0">  <span>  [social=circleundefined]</span></p>'
              }
            }
          ]
        }
      }
    ]
  }
};

//#endregion

//#region Example 9 (Double Button)
const ex9: Data = {
  html: `<div class="text-content text-1 editable" data-id="142222" data-category="text"><div><p data-uniq-id="aCG_1" data-generated-css="brz-css-kcw7Q" style="font-size: 15px; letter-spacing: -0.1px; line-height: 1.4;"><span class="clovercustom" style="font-size: inherit; font-weight: inherit; letter-spacing: inherit;"><u>Our approach </u>to design is based on the art of combining style and functionality. We strive to create spaces that are not only visually impressive, <u>but also serve as comfortable</u> and practical living environments.</span></p><p data-uniq-id="aCG_1" data-generated-css="brz-css-kcw7Q" style="font-size: 15px; letter-spacing: -0.1px; line-height: 1.4;"><span class="clovercustom" style="font-size: inherit; font-weight: inherit; letter-spacing: inherit;"><span data-socialicon="apple"><span class="socialIconSymbol" aria-hidden="true"></span><span class="sr-only">apple</span></span> </span></p><p><br></p><p data-uniq-id="sSLWq" data-generated-css="brz-css-hYqTF" style="font-size: 15px; font-weight: 600; letter-spacing: -0.1px; line-height: 1.4;"><span class="clovercustom" style="font-size: inherit; font-weight: inherit; letter-spacing: inherit;">Be inspired by our designs where modern lines blend with classic elements to create harmony and coziness. We play with colors, textures and light to ensure that every corner of your home is filled with warmth and style.</span></p><p data-uniq-id="sSLWq" data-generated-css="brz-css-hYqTF" style="font-size: 15px; font-weight: 600; letter-spacing: -0.1px; line-height: 1.4;"><span class="clovercustom" style="font-size: inherit; font-weight: inherit; letter-spacing: inherit;"><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" style="font-size: 19px; letter-spacing: -0.1px; line-height: 1.3; text-align: right; color: rgb(9, 51, 237);"><span class="clovercustom" style="font-size: inherit; font-weight: inherit; letter-spacing: inherit;">Our team of talented designers and architects are ready to make your dreams come true.&nbsp;</span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" style="font-size: 19px; letter-spacing: -0.1px; line-height: 1.3;"><span class="clovercustom" style="font-size: inherit; font-weight: inherit; letter-spacing: inherit;"><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" style="font-size: 19px; letter-spacing: -0.1px; line-height: 1.3; text-align: center; color: rgb(242, 3, 55);"><span class="clovercustom" style="font-size: inherit; font-weight: inherit; letter-spacing: inherit;"><span class="clovercustom" style="letter-spacing: -0.1px;">Our team of talented designers and architects are ready to make your dreams come true.&nbsp;</span><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" style="font-size: 19px; letter-spacing: -0.1px; line-height: 1.3; text-align: center;"><span class="clovercustom" style="font-size: inherit; font-weight: inherit; letter-spacing: inherit;"><span class="clovercustom" style="letter-spacing: -0.1px;"><br></span></span></p><ul style="font-size: medium; letter-spacing: normal;"><li data-uniq-id="vteAp" data-generated-css="brz-css-hf4DG" style="font-size: 14px; letter-spacing: -0.1px; line-height: 1.4; text-align: left;"><span class="clovercustom" style="font-size: inherit; font-weight: inherit; letter-spacing: inherit;">We approach each project carefully, taking into account your preferences,<span class="clovercustom">&nbsp;</span><span class="clovercustom">budget and individual space features.</span></span></li><li data-uniq-id="vteAp" data-generated-css="brz-css-hf4DG" style="font-size: 14px; letter-spacing: -0.1px; line-height: 1.4; text-align: left; color: rgb(22, 222, 38);">We approach each project carefully, taking into account your preferences, budget and individual space features.</li></ul><p><a href="http://google.com" class="sites-button cloverlinks" role="button" data-location="external" data-detail="http://google.com" data-category="button" target="_blank"><span class="clovercustom" style="text-align: center;">google</span></a><span class="clovercustom" style="text-align: center;"> </span></p><p><a href="http://google.com" class="sites-button cloverlinks" role="button" data-location="external" data-detail="http://google.com" data-category="button" target="_blank"><span class="clovercustom" style="text-align: center;">google</span></a><span class="clovercustom" style="text-align: center;"> </span></p></div></div>`,
  //prettier-ignore
  entry: {...entry, selector: "[data-id=\"142222\"]"},
  output: {
    data: [
      {
        type: "Wrapper",
        value: {
          _id: "1",
          _styles: ["wrapper", "wrapper--richText"],
          items: [
            {
              type: "RichText",
              value: {
                _id: "1",
                _styles: ["richText"],
                text: '<p data-uniq-id="aCG_1" data-generated-css="brz-css-kcw7Q" class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: inherit; "><u><span>Our approach </span></u>to design is based on the art of combining style and functionality. We strive to create spaces that are not only visually impressive, <u><span>but also serve as comfortable</span></u> and practical living environments.</span></p>'
              }
            }
          ]
        }
      },
      {
        type: "Cloneable",
        value: {
          _id: "1",
          _styles: ["wrapper-clone", "wrapper-clone--icon"],
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                customSize: 26,
                name: "apple",
                padding: 7
              }
            }
          ],
          horizontalAlign: undefined
        }
      },
      {
        type: "Wrapper",
        value: {
          _id: "1",
          _styles: ["wrapper", "wrapper--richText"],
          items: [
            {
              type: "RichText",
              value: {
                _id: "1",
                _styles: ["richText"],
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p data-uniq-id="sSLWq" data-generated-css="brz-css-hYqTF" class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: inherit; ">Be inspired by our designs where modern lines blend with classic elements to create harmony and coziness. We play with colors, textures and light to ensure that every corner of your home is filled with warmth and style.</span></p><p data-uniq-id="sSLWq" data-generated-css="brz-css-hYqTF" class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-0_0 brz-text-lg-right brz-ls-lg-NaN_0"><span style="font-weight: inherit; color: rgb(9, 51, 237); ">Our team of talented designers and architects are ready to make your dreams come true.&nbsp;</span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-0_0 brz-text-lg-center brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><span style="font-weight: inherit; ">Our team of talented designers and architects are ready to make your dreams come true.&nbsp;</span><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-0_0 brz-text-lg-center brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><span style=""><br></span></span></p><ul><li data-uniq-id="vteAp" data-generated-css="brz-css-hf4DG" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: inherit; ">We approach each project carefully, taking into account your preferences,<span>&nbsp;</span><span style="font-weight: inherit; ">budget and individual space features.</span></span></li><li data-uniq-id="vteAp" data-generated-css="brz-css-hf4DG" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-m_0_1"><span style="color: rgb(22, 222, 38); ">We approach each project carefully, taking into account your preferences, budget and individual space features.</span></li></ul>'
              }
            }
          ]
        }
      },
      {
        type: "Cloneable",
        value: {
          _id: "1",
          _styles: ["wrapper-clone", "wrapper-clone--button"],
          items: [
            {
              type: "Button",
              value: {
                _id: "1",
                _styles: ["button"],
                text: "google",
                borderStyle: "none",
                linkExternal: "http://google.com/",
                linkType: "external",
                linkExternalBlank: "on"
              }
            }
          ],
          horizontalAlign: undefined
        }
      },
      {
        type: "Cloneable",
        value: {
          _id: "1",
          _styles: ["wrapper-clone", "wrapper-clone--button"],
          items: [
            {
              type: "Button",
              value: {
                _id: "1",
                _styles: ["button"],
                text: "google",
                borderStyle: "none",
                linkExternal: "http://google.com/",
                linkType: "external",
                linkExternalBlank: "on"
              }
            }
          ],
          horizontalAlign: undefined
        }
      }
    ]
  }
};
//#endregion

//#region Example 10 (The background is not set to transparent when I have rgba(0, 0, 0, 0) and opacity: 1)

const ex10: Data = {
  html: `<div class="text-content text-1 editable" data-id="141588" data-category="text"><div><p><br></p><p style="text-align: center; font-weight: 700; font-family: &quot;League Gothic&quot;, sans-serif; color: rgb(5, 3, 5);"><br></p><p style="text-align: center; font-weight: 400; font-family: &quot;League Gothic&quot;, sans-serif;"><a href="/about-us/who-we-are" class="sites-button cloverlinks" role="button" data-location="existing" data-detail="23432" data-category="button" target="_self" style="font-weight: 400; font-size: 0.8291em;"><span class="clovercustom" style="color: rgb(10, 9, 10); text-align: left; font-weight: 600; font-size: 1.6153em; font-family: Dosis, &quot;Dosis Regular&quot;, sans-serif;">I'm New</span><span data-socialicon="circlerightarrow"><span class="socialIconSymbol" aria-hidden="true"></span><span class="sr-only">circlerightarrow</span></span>&hairsp;</a>&hairsp;<br></p><p style="text-align: center; font-weight: 300;"><br></p><p style="text-align: center; font-weight: 300;"><br></p><p style="text-align: center; font-weight: 300;"><br></p></div><div><div data-type="text"><p class="brz-fs-lg-16 brz-ff-uid2222 brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_2 brz-text-lg-left brz-ls-lg-0"><br></p><p class="brz-fs-lg-16 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_2 brz-text-lg-center brz-ls-lg-0"><br></p></div><div data-type="text"><p class="brz-fs-lg-16 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_2 brz-text-lg-center brz-ls-lg-0">&hairsp;<br></p></div><div data-type="button"><p style="text-align: center; font-weight: 400; font-family: &quot;League Gothic&quot;, sans-serif;"><a href="/about-us/who-we-are" class="sites-button cloverlinks" role="button" data-location="existing" data-detail="23432" data-category="button" target="_self" style="font-weight: 400; font-size: 0.8291em;"><span class="clovercustom" style="color: rgb(10, 9, 10); text-align: left; font-weight: 600; font-size: 1.6153em; font-family: Dosis, &quot;Dosis Regular&quot;, sans-serif;">I'm New</span>&hairsp;</a>&hairsp;<br></p></div><div data-type="text"><p class="brz-fs-lg-16 brz-ff-uid2222 brz-ft-upload brz-fw-lg-300 brz-lh-lg-1_2 brz-text-lg-center brz-ls-lg-0"><br></p><p class="brz-fs-lg-16 brz-ff-uid2222 brz-ft-upload brz-fw-lg-300 brz-lh-lg-1_2 brz-text-lg-center brz-ls-lg-0"><br></p><p class="brz-fs-lg-16 brz-ff-uid2222 brz-ft-upload brz-fw-lg-300 brz-lh-lg-1_2 brz-text-lg-center brz-ls-lg-0"><br></p></div></div></div>`,
  //prettier-ignore
  entry: {...entry, selector: "[data-id=\"141588\"]"},
  output: {
    data: [
      {
        type: "Wrapper",
        value: {
          _id: "1",
          _styles: ["wrapper", "wrapper--richText"],
          items: [
            {
              type: "RichText",
              value: {
                _id: "1",
                _styles: ["richText"],
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0"><br></p>'
              }
            }
          ]
        }
      },
      {
        type: "Cloneable",
        value: {
          _id: "1",
          _styles: ["wrapper-clone", "wrapper-clone--button"],
          items: [
            {
              type: "Button",
              value: {
                _id: "1",
                _styles: ["button"],
                text: "I'm New ",
                borderStyle: "none",
                iconName: "circle-right-37",
                linkExternal: "http://localhost/about-us/who-we-are",
                linkExternalBlank: "off",
                linkType: "external"
              }
            }
          ],
          horizontalAlign: undefined
        }
      },
      {
        type: "Wrapper",
        value: {
          _id: "1",
          _styles: ["wrapper", "wrapper--richText"],
          items: [
            {
              type: "RichText",
              value: {
                _id: "1",
                _styles: ["richText"],
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-NaN_0"><br></p>'
              }
            }
          ]
        }
      }
    ]
  }
};

//#endregion

describe.each([ex1, ex2, ex3, ex4, ex5, ex6, ex7, ex8, ex9, ex10])(
  "testing 'getText' function nr %#",
  ({ entry, output, html }) => {
    beforeEach(() => {
      document.body.innerHTML = html;
    });

    test("expected", () => {
      expect(getText(entry)).toStrictEqual(output);
    });
  }
);

describe("testing 'getText' error function", () => {
  test.each<[Entry, Output]>([
    // Default
    [entry, { error: "Element with selector test not found" }]
  ])("getText nr %#", (entry, output) => {
    expect(getText(entry)).toStrictEqual(output);
  });
});
