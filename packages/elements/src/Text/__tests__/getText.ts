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
                iconName: "arrow-alt-circle-right",
                linkExternal: "/about-us/who-we-are",
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
                name: "facebook-square",
                type: "fa"
              }
            },
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                padding: 7,
                customSize: 26,
                name: "twitter",
                type: "fa"
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
                name: "envelope",
                type: "fa"
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p data-uniq-id="sSLWq" data-generated-css="brz-css-hYqTF" class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><u><span>Be inspired by our designs where modern lines blend with classic elements to create harmony and coziness. We play with colors, textures and light to ensure that every corner of your home is filled with warmth and style.</span></u></span></p><p data-uniq-id="sSLWq" data-generated-css="brz-css-hYqTF" class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-0_0 brz-text-lg-right brz-ls-lg-NaN_0"><span style="font-weight: inherit; color: rgb(235, 9, 9); ">Our team of talented designers and architects are ready to make your dreams come true.&nbsp;</span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-2 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-NaN brz-text-lg-center brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><span style="font-weight: inherit; ">Our team of talented designers and architects are ready to make your dreams come true.&nbsp;</span><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-0_0 brz-text-lg-center brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><span style=""><br></span></span></p><ul><li data-uniq-id="vteAp" data-generated-css="brz-css-hf4DG" style="color: rgb(70, 242, 12); " class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: inherit; color: rgb(70, 242, 12); ">We approach each project carefully, taking into account your preferences, budget and individual space features.</span></li><li data-uniq-id="vteAp" data-generated-css="brz-css-hf4DG" style="color: rgb(222, 95, 22); " class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-m_0_1"><span style="color: rgb(222, 95, 22); ">We approach each project carefully, taking into account your preferences, budget and individual space features.</span></li></ul>'
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
                padding: 7,
                type: "fa"
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p data-uniq-id="sSLWq" data-generated-css="brz-css-hYqTF" class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: inherit; ">Be inspired by our designs where modern lines blend with classic elements to create harmony and coziness. We play with colors, textures and light to ensure that every corner of your home is filled with warmth and style.</span></p><p data-uniq-id="sSLWq" data-generated-css="brz-css-hYqTF" class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-0_0 brz-text-lg-right brz-ls-lg-NaN_0"><span style="font-weight: inherit; color: rgb(9, 51, 237); ">Our team of talented designers and architects are ready to make your dreams come true.&nbsp;</span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-0_0 brz-text-lg-center brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><span style="font-weight: inherit; ">Our team of talented designers and architects are ready to make your dreams come true.&nbsp;</span><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-0_0 brz-text-lg-center brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><span style=""><br></span></span></p><ul><li data-uniq-id="vteAp" data-generated-css="brz-css-hf4DG" style="" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: inherit; ">We approach each project carefully, taking into account your preferences,<span>&nbsp;</span><span style="font-weight: inherit; ">budget and individual space features.</span></span></li><li data-uniq-id="vteAp" data-generated-css="brz-css-hf4DG" style="color: rgb(22, 222, 38); " class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-m_0_1"><span style="color: rgb(22, 222, 38); ">We approach each project carefully, taking into account your preferences, budget and individual space features.</span></li></ul>'
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
                name: "user-run",
                type: "fa"
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
                name: "facebook-square",
                type: "fa"
              }
            },
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                padding: 7,
                customSize: 26,
                name: "twitter",
                type: "fa"
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
                name: "facebook-square",
                type: "fa"
              }
            },
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                padding: 7,
                customSize: 26,
                name: "twitter",
                type: "fa"
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
                padding: 7,
                type: "fa"
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p data-uniq-id="sSLWq" data-generated-css="brz-css-hYqTF" class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: inherit; ">Be inspired by our designs where modern lines blend with classic elements to create harmony and coziness. We play with colors, textures and light to ensure that every corner of your home is filled with warmth and style.</span></p><p data-uniq-id="sSLWq" data-generated-css="brz-css-hYqTF" class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-0_0 brz-text-lg-right brz-ls-lg-NaN_0"><span style="font-weight: inherit; color: rgb(9, 51, 237); ">Our team of talented designers and architects are ready to make your dreams come true.&nbsp;</span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-0_0 brz-text-lg-center brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><span style="font-weight: inherit; ">Our team of talented designers and architects are ready to make your dreams come true.&nbsp;</span><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-0_0 brz-text-lg-center brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><span style=""><br></span></span></p><ul><li data-uniq-id="vteAp" data-generated-css="brz-css-hf4DG" style="" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: inherit; ">We approach each project carefully, taking into account your preferences,<span>&nbsp;</span><span style="font-weight: inherit; ">budget and individual space features.</span></span></li><li data-uniq-id="vteAp" data-generated-css="brz-css-hf4DG" style="color: rgb(22, 222, 38); " class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-m_0_1"><span style="color: rgb(22, 222, 38); ">We approach each project carefully, taking into account your preferences, budget and individual space features.</span></li></ul>'
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
                iconName: "arrow-alt-circle-right",
                linkExternal: "/about-us/who-we-are",
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

//#region Example 11 (Rounded the line-height)

const ex11: Data = {
  html: `<header class="text-content text-0 title-text editable" data-id="24167954" data-category="text"><div><p>Discipleships<br></p></div></header>`,
  //prettier-ignore
  entry: {...entry, selector: "[data-id=\"24167954\"]"},
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><span>Discipleships</span><br></p>'
              }
            }
          ]
        }
      }
    ]
  }
};
//#endregion

//#region Example 12 (Add margins class to a list)
const ex12: Data = {
  html: `<div class="text-content text-1 editable" data-id="24213357" data-category="text" id="accordion-24213352-body" role="region" aria-labelledby="accordion-24213352-title"><div><p style="font-size: 1.25em;"><u>Nursery</u>&nbsp;&nbsp;</p><ul style="color: rgb(174, 116, 116);font-family: Aleo, &quot;Aleo Light&quot;, serif;font-style: normal;font-weight: 200;margin: 15px 0;"><li style="color: rgb(228, 233, 238);">We have 4 spots open in our nursery!</li><li style="color: rgb(228, 233, 238);">The nursery includes babies and toddlers from 6 weeks old through 2 years, 11 months</li><li style="color: rgb(228, 233, 238);">All volunteers must be willing to submit to a general background check</li><li style="color: rgb(228, 233, 238);">Please see our bulletin board in the main hallway or ask Bailey Stewart for a description of the specific duties and roles</li></ul><div style="font-size: 1.25em;"><u>Children's Church (ARROW Kids)</u></div><div><ul><li>We have 4 spots open with ARROW Kids Sunday Morning Children's Church!&nbsp;</li><li>ARROW Kids is designed for children ages 3 through 10</li><li>Volunteers are provided a fun, easy to follow lesson plan each week including Bible stories, games, and crafts</li><li>All volunteers must be willing to submit to a general background check</li><li>Please see our bulletin board in the main hallway, or ask Crystal Williford, for more information on duties and roles</li></ul></div></div></div>`,
  //prettier-ignore
  entry: {...entry, selector: "[data-id=\"24213357\"]"},
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><u><span>Nursery</span></u>&nbsp;&nbsp;</p><ul><li style="color: rgb(228, 233, 238); " class="brz-mt-lg-15 brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(228, 233, 238); ">We have 4 spots open in our nursery!</span></li><li style="color: rgb(228, 233, 238); " class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(228, 233, 238); ">The nursery includes babies and toddlers from 6 weeks old through 2 years, 11 months</span></li><li style="color: rgb(228, 233, 238); " class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(228, 233, 238); ">All volunteers must be willing to submit to a general background check</span></li><li style="color: rgb(228, 233, 238); " class="brz-mb-lg-15 brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(228, 233, 238); ">Please see our bulletin board in the main hallway or ask Bailey Stewart for a description of the specific duties and roles</span></li></ul><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><u><span>Children\'s Church (ARROW Kids)</span></u></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"></p><ul><li class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><span>We have 4 spots open with ARROW Kids Sunday Morning Children\'s Church!&nbsp;</span></li><li class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><span>ARROW Kids is designed for children ages 3 through 10</span></li><li class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><span>Volunteers are provided a fun, easy to follow lesson plan each week including Bible stories, games, and crafts</span></li><li class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><span>All volunteers must be willing to submit to a general background check</span></li><li class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><span>Please see our bulletin board in the main hallway, or ask Crystal Williford, for more information on duties and roles</span></li></ul><p></p>'
              }
            }
          ]
        }
      }
    ]
  }
};
//#endregion

//#region Example 13 (Button url)

const ex13: Data = {
  html: `<div class="text-content text-1 editable" data-id="13193659" data-category="text"><div><p style="font-family: &quot;League Spartan&quot;, sans-serif; font-weight: 400; font-size: 2.3271em; color: rgb(2, 78, 105);">What is it?</p><p style="font-family: Roboto, &quot;Roboto Light&quot;, sans-serif; font-weight: 300; font-size: 0.9973em; color: rgb(2, 78, 105);"><o:p>&nbsp;</o:p></p><p style="color: rgb(2, 78, 105);"><span class="clovercustom" style="font-size: 1.3298em;">Baptism is an outward expression of an inward faith. It’s showing everyone that you’ve confessed (you’ve said it out loud) that Jesus is Lord and believed in your heart God raised Him from the dead. Baptism is a way to symbolize the death and resurrection of Christ in our lives as believers.</span></p><p style="color: rgb(2, 78, 105);"><o:p>&nbsp;</o:p></p><p style="color: rgb(2, 78, 105);"><span class="clovercustom" style="font-family: &quot;League Spartan&quot;, sans-serif; font-weight: 400; font-size: 2.3271em;">Why submersion?</span></p><p style="color: rgb(2, 78, 105);"><br></p><p style="color: rgb(2, 78, 105);"><span class="clovercustom" style="font-size: 1.3298em;">We believe that by being placed completely underwater, baptism by submersion symbolizes the death and resurrection of Jesus in our lives as believers. Complete submersion also represents the total spiritual change a person experiences by being rescued and saved by Jesus.</span></p><p style="color: rgb(2, 78, 105);"><o:p>&nbsp;</o:p></p><p style="color: rgb(2, 78, 105);"><span lang="EN-US" class="clovercustom" style="font-family: &quot;League Spartan&quot;, sans-serif; font-weight: 400; font-size: 2.3271em;">Who should be baptized?</span></p><p style="color: rgb(2, 78, 105);"><br></p><p style="color: rgb(2, 78, 105);"><span lang="EN-US" class="clovercustom" style="font-size: 1.3298em;">If you have confessed with your mouth that Jesus is Lord, repented of your sin, and believed that God raised Him from the dead, and you’ve never been baptized before, your next step is to be baptized. Confessing, repenting, and believing makes you a Christian and all Christians are commanded by Jesus to be baptized. (Matthew 28:19) We encourage you to take your next step as a believer to be baptized and publicly share your faith in Him.</span></p><p style="color: rgb(2, 78, 105);"><o:p>&nbsp;</o:p></p><p style="color: rgb(2, 78, 105);"><span lang="EN-US" class="clovercustom" style="font-family: &quot;League Spartan&quot;, sans-serif; font-weight: 400; font-size: 2.3271em;">How can I be baptized?</span></p><p style="color: rgb(2, 78, 105);"><o:p>&nbsp;</o:p></p><p style="color: rgb(2, 78, 105);"><o:p>&nbsp;</o:p><span class="clovercustom" style="font-size: 1.3298em;">We have baptisms throughout the year and would love for you to be a part of one of the next one! <a href="/contact-us/decision-form" data-location="existing" data-detail="879092" data-category="link" target="_self" class="cloverlinks" style="color: rgb(17, 125, 2);">Contact us</a> so we can discuss you next step.</span></p><p><br></p><p><br></p><p></p><center><p><span class="clovercustom" style="text-align: center; font-size: 1.6029em;"><span class="clovercustom" style="font-size: 0.7407em;"><a href="/contact-us/decision-form" class="sites-button cloverlinks" role="button" data-location="existing" data-detail="879092" data-category="button" target="_self">Get Baptized</a></span></span></p> <p style="text-align: left; font-weight: 700; font-size: 2.6596em; font-family: &quot;League Spartan&quot;, sans-serif;"><span class="clovercustom" style="font-size: 34.9996px; letter-spacing: 0.1504px;">How can I help?</span><br></p><p style="text-align: left; font-size: 1.2633em;">We need dedicated volunteers to help prepare the baptismal every Baptismal Sunday.&nbsp; This includes set-up, tear-down, and providing necessary support to the ones being baptized.&nbsp;<br></p> <p><span class="clovercustom" style="text-align: center; font-size: 1.6029em;"><br></span></p> <p style="font-size: 1.0638em;"><a href="https://docs.google.com/forms/d/1Bt4ajELyGHt1jYtkf7X9QG_FTKcqX0p5r9xcxSCckYk/edit" class="sites-button cloverlinks" role="button" data-location="external" data-detail="https://docs.google.com/forms/d/1Bt4ajELyGHt1jYtkf7X9QG_FTKcqX0p5r9xcxSCckYk/edit" data-category="button" target="_blank">join the baptism support team</a></p></center><p></p><p><br></p><p><style> </style></p></div></div>`,
  //prettier-ignore
  entry: {...entry, selector: "[data-id=\"13193659\"]"},
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
                text: '<p class="brz-fs-lg-2 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); font-weight: 400; ">What is it?</span></p><p class="brz-fs-lg-0 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><o:p>&nbsp;</o:p></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">Baptism is an outward expression of an inward faith. It’s showing everyone that you’ve confessed (you’ve said it out loud) that Jesus is Lord and believed in your heart God raised Him from the dead. Baptism is a way to symbolize the death and resurrection of Christ in our lives as believers.</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><o:p>&nbsp;</o:p></p><p class="brz-fs-lg-2 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 400; color: rgb(2, 78, 105); ">Why submersion?</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">We believe that by being placed completely underwater, baptism by submersion symbolizes the death and resurrection of Jesus in our lives as believers. Complete submersion also represents the total spiritual change a person experiences by being rescued and saved by Jesus.</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><o:p>&nbsp;</o:p></p><p class="brz-fs-lg-2 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><span lang="EN-US" style="font-weight: 400; color: rgb(2, 78, 105); ">Who should be baptized?</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><span lang="EN-US" style="color: rgb(2, 78, 105); ">If you have confessed with your mouth that Jesus is Lord, repented of your sin, and believed that God raised Him from the dead, and you’ve never been baptized before, your next step is to be baptized. Confessing, repenting, and believing makes you a Christian and all Christians are commanded by Jesus to be baptized. (Matthew 28:19) We encourage you to take your next step as a believer to be baptized and publicly share your faith in Him.</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><o:p>&nbsp;</o:p></p><p class="brz-fs-lg-2 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><span lang="EN-US" style="font-weight: 400; color: rgb(2, 78, 105); ">How can I be baptized?</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><o:p>&nbsp;</o:p></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><o:p>&nbsp;</o:p><span style="color: rgb(2, 78, 105); ">We have baptisms throughout the year and would love for you to be a part of one of the next one! <a href="/contact-us/decision-form" data-location="existing" data-detail="879092" data-category="link" target="_self" style="color: rgb(17, 125, 2); "><span style="color: rgb(17, 125, 2); ">Contact us</span></a> so we can discuss you next step.</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"></p>'
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
                text: "Get Baptized",
                borderStyle: "none",
                linkExternal: "/contact-us/decision-form",
                linkExternalBlank: "off",
                linkType: "external"
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
                text: "join the baptism support team",
                borderStyle: "none",
                linkExternal:
                  "https://docs.google.com/forms/d/1Bt4ajELyGHt1jYtkf7X9QG_FTKcqX0p5r9xcxSCckYk/edit",
                linkExternalBlank: "on",
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
                text: '<center><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><span style=""><span style=""></span></span></p> <p class="brz-fs-lg-34 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-0_1"><span style="font-weight: 700; ">How can I help?</span><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><span>We need dedicated volunteers to help prepare the baptismal every Baptismal Sunday.&nbsp; This includes set-up, tear-down, and providing necessary support to the ones being baptized.&nbsp;</span><br></p> <p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><span style=""><br></span></p> <p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"></p></center>'
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
                text: '<center><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><span style=""><span style=""></span></span></p> <p class="brz-fs-lg-34 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-0_0 brz-text-lg-left brz-ls-lg-0_1"><span style="font-weight: 700; ">How can I help?</span><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><span>We need dedicated volunteers to help prepare the baptismal every Baptismal Sunday.&nbsp; This includes set-up, tear-down, and providing necessary support to the ones being baptized.&nbsp;</span><br></p> <p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><span style=""><br></span></p> <p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"></p></center>'
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-NaN brz-text-lg-left brz-ls-lg-NaN_0"><style> </style></p>'
              }
            }
          ]
        }
      }
    ]
  }
};

//#endregion

describe.each([
  ex1,
  ex2,
  ex3,
  ex4,
  ex5,
  ex6,
  ex7,
  ex8,
  ex9,
  ex10,
  ex11,
  ex12,
  ex13
])("testing 'getText' function nr %#", ({ entry, output, html }) => {
  beforeEach(() => {
    document.body.innerHTML = html;
  });

  test("expected", () => {
    expect(getText(entry)).toStrictEqual(output);
  });
});

describe("testing 'getText' error function", () => {
  test.each<[Entry, Output]>([
    // Default
    [entry, { error: "Element with selector test not found" }]
  ])("getText nr %#", (entry, output) => {
    expect(getText(entry)).toStrictEqual(output);
  });
});
