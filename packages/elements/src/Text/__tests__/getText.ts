/* eslint-disable quotes, no-irregular-whitespace */
import { getText } from "../";
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
                "text": "<p class=\"brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0\"><br></p><p class=\"brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0\"><br></p>"
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
          horizontalAlign: "center",
          items: [
            {
              type: "Button",
              value: {
                _id: "1",
                _styles: ["button"],
                text: "I'm New",
                borderStyle: "none",
                fontFamily: "lato",
                fontFamilyType: "upload",
                fontSize: 1,
                fontStyle: "",
                fontWeight: 600,
                iconName: "arrow-alt-circle-right",
                iconType: "fa",
                lineHeight: 1.3,
                linkExternal: "/about-us/who-we-are",
                linkType: "external",
                mobileFontSize: 1,
                mobileFontStyle: "",
                mobileFontWeight: 600,
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontSize: 1,
                tabletFontStyle: "",
                tabletFontWeight: 600,
                tabletLineHeight: 1.2,
                linkExternalBlank: "off",
                colorHex: "#0a090a",
                colorOpacity: 1,
                colorPalette: ""
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
                "text": "<p class=\"brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0\"><br></p><p class=\"brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0\"><br></p><p class=\"brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0\"><br></p>"
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
  entry: { ...entry, selector: "[data-id=\"15600705\"]" },
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Please </span><span style="font-weight: 700; ">Request Online Directory Account</span><span> to create a User Account to login into the Directory portal.&nbsp; &nbsp;Please insert your Name, Address, Phone and Email, so we can update our records.&nbsp; &nbsp;Please be patient with us, we are getting our records in order and it make take a couple days before you get an email with your login credentials.&nbsp;</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 700; ">Directory </span><span>will redirect to </span><span style="font-weight: 400; "><span style="font-weight: 700; ">Shelby Next Membership</span> at <a data-location="external" data-detail="https://fbcterry.shelbynextchms.com" data-category="link" target="_blank" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Ffbcterry.shelbynextchms.com%2F%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span>https://fbcterry.shelbynextchms.com</span></a></span><span>.</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Other ways to access </span><span style="font-weight: 700; ">Shelby Next Membership</span><span> can be found at your Apple App Store or Google Play Store.&nbsp;</span><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>For mobile apps, change </span><span style="font-weight: 700; ">domain</span><span>.shelbynextchms.com to </span><u><span style="font-weight: 700; ">fbcterry</span><span>.shelbynextchms.com</span></u><span>.&nbsp;&nbsp;</span></p><ul> <li class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"> <a data-category="link" data-location="external" data-detail="https://apps.apple.com/us/app/shelbynext-membership/id996581065" target="_self" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fapps.apple.com%2Fus%2Fapp%2Fshelbynext-membership%2Fid996581065%22%2C%22externalBlank%22%3A%22off%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span>iPhone/iPad app for Shelby Next Membership</span></a> </li> <li class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"> <a data-category="link" data-location="external" data-detail="https://play.google.com/store/apps/details?id=com.ministrybrands.shelbynext" target="_self" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fplay.google.com%2Fstore%2Fapps%2Fdetails%3Fid%3Dcom.ministrybrands.shelbynext%22%2C%22externalBlank%22%3A%22off%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span>Android app for Shelby Next Membership</span></a> <br> </li> </ul><p class="brz-fs-lg-0_94 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-0_0"><span style="font-weight: 700; ">Calendar </span><span>is our Church-wide calendar.</span><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p>'
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
  entry: { ...entry, selector: "[data-id=\"141413\"]" },
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
                text: `<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span>Address: 404 Church St. Columbia, LA 71418</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span>Phone Number: 318-649-2202</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span>Fax Number: 318-649-2206</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span>Email: fbcbeyond@bellsouth.net</span></p><p class="brz-fs-lg-1_07 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><br></p>`
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
                text: '<p class="brz-fs-lg-1_07 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"> <span> [social=circleundefined]</span><br></p>'
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
  entry: { ...entry, selector: "[data-id=\"141447\"]" },
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
                text: `<p class="brz-fs-lg-1_85 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; "> We are a church whose goal is to make followers of Jesus Christ.&nbsp;</em></p><p class="brz-fs-lg-1_4 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">We believe that Jesus Christ died on the cross for our sins,</em></p><p class="brz-fs-lg-1_4 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; "> was buried, and on the third day rose from the dead!&nbsp;</em></p><p class="brz-fs-lg-1_4 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">We believe that everyone who turns from their sins and believes in Jesus Christ will be saved. How?&nbsp;</em></p><p class="brz-fs-lg-1_4 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">One calls on the name of Jesus for salvation!</em></p><p class="brz-fs-lg-1_4 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_04 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span style="color: rgb(230, 230, 189); font-weight: 700; ">Our worship services here in Shady<span style="color: rgb(230, 230, 189); font-weight: 700; "> Side, Maryla</span>nd are 10–11am each Sunday morning</span><em style="color: rgb(230, 230, 189); font-weight: 700; ">.</em></p><p class="brz-fs-lg-1_4 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">During this time, we will read Scripture, pray together, sing worship songs,&nbsp;</em></p><p class="brz-fs-lg-1_4 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">and listen to the Bible preached.</em></p><p class="brz-fs-lg-1_4 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_4 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">We hope you will come and feel welcome as you listen to God’s word to us in the Bible.&nbsp;</em></p><p class="brz-fs-lg-1_4 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">Our Pastor and Elders are here for you if you have any questions about our church, baptism, or</em></p><p class="brz-fs-lg-1_4 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">following Jesus. Our atmosphere is casual, so do not feel like you need to dress up to attend.&nbsp;</em></p><p class="brz-fs-lg-1_4 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">We hope you will browse our website to find out more about our church,&nbsp;</em></p><p class="brz-fs-lg-1_4 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">our Baptist beliefs, and our ministries.</em></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><em style="color: red; ">Test</em></p><p class="brz-fs-lg-1_4 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0">&nbsp;</p>`
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
  entry: { ...entry, selector: "[data-id=\"142241\"]" },
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
                text: '<p data-uniq-id="aCG_1" data-generated-css="brz-css-kcw7Q" class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: inherit; "><u><span>Our approach </span></u>to design is based on the art of combining style and functionality. We strive to create spaces that are not only visually impressive, <u><span>but also serve as comfortable</span></u> and practical living environments.</span></p><p data-uniq-id="aCG_1" data-generated-css="brz-css-kcw7Q" class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><br></p>'
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p data-uniq-id="sSLWq" data-generated-css="brz-css-hYqTF" class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><u><span>Be inspired by our designs where modern lines blend with classic elements to create harmony and coziness. We play with colors, textures and light to ensure that every corner of your home is filled with warmth and style.</span></u></span></p><p data-uniq-id="sSLWq" data-generated-css="brz-css-hYqTF" class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-right brz-ls-lg-NaN_0"><span style="font-weight: inherit; color: rgb(235, 9, 9); ">Our team of talented designers and architects are ready to make your dreams come true.&nbsp;</span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-2_52 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><span style="font-weight: inherit; ">Our team of talented designers and architects are ready to make your dreams come true.&nbsp;</span><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><span style=""><br></span></span></p><ul><li data-uniq-id="vteAp" data-generated-css="brz-css-hf4DG" style="color: rgb(70, 242, 12); " class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: inherit; color: rgb(70, 242, 12); ">We approach each project carefully, taking into account your preferences, budget and individual space features.</span></li><li data-uniq-id="vteAp" data-generated-css="brz-css-hf4DG" style="color: rgb(222, 95, 22); " class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><span style="color: rgb(222, 95, 22); ">We approach each project carefully, taking into account your preferences, budget and individual space features.</span></li></ul>'
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
                fontFamily: "lato",
                fontFamilyType: "upload",
                fontStyle: "",
                mobileFontStyle: "",
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontStyle: "",
                tabletLineHeight: 1.2,
                lineHeight: 1.3,
                linkExternal: "http://google.com/",
                linkType: "external",
                linkExternalBlank: "on",
                iconName: ""
              }
            }
          ],
          horizontalAlign: "center"
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
  entry: { ...entry, selector: "[data-id=\"142239\"]" },
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
                text: '<p data-uniq-id="aCG_1" data-generated-css="brz-css-kcw7Q" class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: inherit; "><u><span>Our approach </span></u>to design is based on the art of combining style and functionality. We strive to create spaces that are not only visually impressive, <u><span>but also serve as comfortable</span></u> and practical living environments.</span></p>'
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p data-uniq-id="sSLWq" data-generated-css="brz-css-hYqTF" class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: inherit; ">Be inspired by our designs where modern lines blend with classic elements to create harmony and coziness. We play with colors, textures and light to ensure that every corner of your home is filled with warmth and style.</span></p><p data-uniq-id="sSLWq" data-generated-css="brz-css-hYqTF" class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-right brz-ls-lg-NaN_0"><span style="font-weight: inherit; color: rgb(9, 51, 237); ">Our team of talented designers and architects are ready to make your dreams come true.&nbsp;</span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><span style="font-weight: inherit; ">Our team of talented designers and architects are ready to make your dreams come true.&nbsp;</span><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><span style=""><br></span></span></p><ul><li data-uniq-id="vteAp" data-generated-css="brz-css-hf4DG" style="" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: inherit; ">We approach each project carefully, taking into account your preferences,<span>&nbsp;</span><span style="font-weight: inherit; ">budget and individual space features.</span></span></li><li data-uniq-id="vteAp" data-generated-css="brz-css-hf4DG" style="color: rgb(22, 222, 38); " class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><span style="color: rgb(22, 222, 38); ">We approach each project carefully, taking into account your preferences, budget and individual space features.</span></li></ul>'
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
                fontFamily: "lato",
                fontFamilyType: "upload",
                fontStyle: "",
                lineHeight: 1.3,
                mobileFontStyle: "",
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontStyle: "",
                tabletLineHeight: 1.2,
                linkExternal: "http://google.com/",
                linkType: "external",
                linkExternalBlank: "on",
                iconName: ""
              }
            }
          ],
          horizontalAlign: "center"
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
  entry: { ...entry, selector: "[data-id=\"141416\"]" },
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
                text: '<p class="brz-fs-lg-1_74 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Register Here!&nbsp;</span><br> </p>'
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>wwww</span></p>'
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
  entry: { ...entry, selector: "[data-id=\"141422\"]" },
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span>Address: 404 Church St. Columbia, LA 71418</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span>Phone Number: 318-649-2202</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span>Fax Number: 318-649-2206</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span>Email: fbcbeyond@bellsouth.net</span></p><p class="brz-fs-lg-1_07 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><br></p>'
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
                text: '<p class="brz-fs-lg-1_07 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"> <span> [social=circleundefined]</span></p>'
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
                text: '<p class="brz-fs-lg-1_07 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"> <span> [social=circleundefined]</span></p>'
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
  entry: { ...entry, selector: "[data-id=\"142222\"]" },
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
                text: '<p data-uniq-id="aCG_1" data-generated-css="brz-css-kcw7Q" class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: inherit; "><u><span>Our approach </span></u>to design is based on the art of combining style and functionality. We strive to create spaces that are not only visually impressive, <u><span>but also serve as comfortable</span></u> and practical living environments.</span></p>'
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p data-uniq-id="sSLWq" data-generated-css="brz-css-hYqTF" class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: inherit; ">Be inspired by our designs where modern lines blend with classic elements to create harmony and coziness. We play with colors, textures and light to ensure that every corner of your home is filled with warmth and style.</span></p><p data-uniq-id="sSLWq" data-generated-css="brz-css-hYqTF" class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-right brz-ls-lg-NaN_0"><span style="font-weight: inherit; color: rgb(9, 51, 237); ">Our team of talented designers and architects are ready to make your dreams come true.&nbsp;</span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><span style="font-weight: inherit; ">Our team of talented designers and architects are ready to make your dreams come true.&nbsp;</span><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><span style=""><br></span></span></p><ul><li data-uniq-id="vteAp" data-generated-css="brz-css-hf4DG" style="" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: inherit; ">We approach each project carefully, taking into account your preferences,<span>&nbsp;</span><span style="font-weight: inherit; ">budget and individual space features.</span></span></li><li data-uniq-id="vteAp" data-generated-css="brz-css-hf4DG" style="color: rgb(22, 222, 38); " class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><span style="color: rgb(22, 222, 38); ">We approach each project carefully, taking into account your preferences, budget and individual space features.</span></li></ul>'
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
                fontFamily: "lato",
                fontFamilyType: "upload",
                fontStyle: "",
                lineHeight: 1.3,
                mobileFontStyle: "",
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontStyle: "",
                tabletLineHeight: 1.2,
                linkExternal: "http://google.com/",
                linkType: "external",
                linkExternalBlank: "on",
                iconName: ""
              }
            }
          ],
          horizontalAlign: "center"
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
                fontFamily: "lato",
                fontFamilyType: "upload",
                fontStyle: "",
                lineHeight: 1.3,
                mobileFontStyle: "",
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontStyle: "",
                tabletLineHeight: 1.2,
                linkExternal: "http://google.com/",
                linkType: "external",
                linkExternalBlank: "on",
                iconName: ""
              }
            }
          ],
          horizontalAlign: "center"
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
                text: `<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><br></p>`
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
                text: "I'm New",
                borderStyle: "none",
                fontFamily: "lato",
                fontFamilyType: "upload",
                fontSize: 1,
                fontStyle: "",
                fontWeight: 600,
                lineHeight: 1.3,
                mobileFontSize: 1,
                mobileFontStyle: "",
                mobileFontWeight: 600,
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontSize: 1,
                tabletFontStyle: "",
                tabletFontWeight: 600,
                tabletLineHeight: 1.2,
                iconName: "arrow-alt-circle-right",
                iconType: "fa",
                linkExternal: "/about-us/who-we-are",
                linkExternalBlank: "off",
                linkType: "external",
                colorHex: "#0a090a",
                colorOpacity: 1,
                colorPalette: ""
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
                text: `<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><br></p>`
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
  entry: { ...entry, selector: "[data-id=\"24167954\"]" },
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Discipleships</span><br></p>'
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
  entry: { ...entry, selector: "[data-id=\"24213357\"]" },
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
                text: `<p class="brz-fs-lg-1_25 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><u><span>Nursery</span></u>&nbsp;&nbsp;</p><ul><li style="color: rgb(228, 233, 238); " class="brz-mt-lg-15 brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(228, 233, 238); ">We have 4 spots open in our nursery!</span></li><li style="color: rgb(228, 233, 238); " class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(228, 233, 238); ">The nursery includes babies and toddlers from 6 weeks old through 2 years, 11 months</span></li><li style="color: rgb(228, 233, 238); " class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(228, 233, 238); ">All volunteers must be willing to submit to a general background check</span></li><li style="color: rgb(228, 233, 238); " class="brz-mb-lg-15 brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(228, 233, 238); ">Please see our bulletin board in the main hallway or ask Bailey Stewart for a description of the specific duties and roles</span></li></ul><p class="brz-fs-lg-1_25 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><u><span>Children's Church (ARROW Kids)</span></u></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"></p><ul><li class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>We have 4 spots open with ARROW Kids Sunday Morning Children's Church!&nbsp;</span></li><li class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>ARROW Kids is designed for children ages 3 through 10</span></li><li class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Volunteers are provided a fun, easy to follow lesson plan each week including Bible stories, games, and crafts</span></li><li class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>All volunteers must be willing to submit to a general background check</span></li><li class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Please see our bulletin board in the main hallway, or ask Crystal Williford, for more information on duties and roles</span></li></ul><p></p>`
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
  entry: { ...entry, selector: "[data-id=\"13193659\"]" },
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
                text: '<p class="brz-fs-lg-2_33 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); font-weight: 400; ">What is it?</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><o:p>&nbsp;</o:p></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">Baptism is an outward expression of an inward faith. It’s showing everyone that you’ve confessed (you’ve said it out loud) that Jesus is Lord and believed in your heart God raised Him from the dead. Baptism is a way to symbolize the death and resurrection of Christ in our lives as believers.</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><o:p>&nbsp;</o:p></p><p class="brz-fs-lg-2_33 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 400; color: rgb(2, 78, 105); ">Why submersion?</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">We believe that by being placed completely underwater, baptism by submersion symbolizes the death and resurrection of Jesus in our lives as believers. Complete submersion also represents the total spiritual change a person experiences by being rescued and saved by Jesus.</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><o:p>&nbsp;</o:p></p><p class="brz-fs-lg-2_33 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span lang="EN-US" style="font-weight: 400; color: rgb(2, 78, 105); ">Who should be baptized?</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span lang="EN-US" style="color: rgb(2, 78, 105); ">If you have confessed with your mouth that Jesus is Lord, repented of your sin, and believed that God raised Him from the dead, and you’ve never been baptized before, your next step is to be baptized. Confessing, repenting, and believing makes you a Christian and all Christians are commanded by Jesus to be baptized. (Matthew 28:19) We encourage you to take your next step as a believer to be baptized and publicly share your faith in Him.</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><o:p>&nbsp;</o:p></p><p class="brz-fs-lg-2_33 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span lang="EN-US" style="font-weight: 400; color: rgb(2, 78, 105); ">How can I be baptized?</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><o:p>&nbsp;</o:p></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><o:p>&nbsp;</o:p><span style="color: rgb(2, 78, 105); ">We have baptisms throughout the year and would love for you to be a part of one of the next one! <a data-location="existing" data-detail="879092" data-category="link" target="_self" style="color: rgb(17, 125, 2); " data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22%2Fcontact-us%2Fdecision-form%22%2C%22externalBlank%22%3A%22off%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span style="color: rgb(17, 125, 2); ">Contact us</span></a> so we can discuss you next step.</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"></p>'
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
                fontFamily: "lato",
                fontFamilyType: "upload",
                fontStyle: "",
                lineHeight: 1.3,
                mobileFontStyle: "",
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontStyle: "",
                tabletLineHeight: 1.2,
                linkExternal: "/contact-us/decision-form",
                linkType: "external",
                linkExternalBlank: "off",
                iconName: ""
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
                text: '<p class="brz-fs-lg-35 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-0_1"><span style="font-weight: 700; ">How can I help?</span><br></p><p class="brz-fs-lg-1_26 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>We need dedicated volunteers to help prepare the baptismal every Baptismal Sunday.&nbsp; This includes set-up, tear-down, and providing necessary support to the ones being baptized.&nbsp;</span><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style=""><br></span></p>'
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
                text: "join the baptism support team",
                borderStyle: "none",
                fontFamily: "lato",
                fontFamilyType: "upload",
                fontStyle: "",
                lineHeight: 1.3,
                mobileFontStyle: "",
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontStyle: "",
                tabletLineHeight: 1.2,
                linkExternal:
                  "https://docs.google.com/forms/d/1Bt4ajELyGHt1jYtkf7X9QG_FTKcqX0p5r9xcxSCckYk/edit",
                linkType: "external",
                linkExternalBlank: "on",
                iconName: ""
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
                text: `<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"></p>`
              }
            }
          ]
        }
      }
    ]
  }
};
//#endregion

//#region Example 14 (Footer with Icons, Button and text)
const ex14: Data = {
  html: `<div class="text-content text-0 editable" data-id="12866076" data-category="text"><div><p style="text-align: center; font-size: 1.0638em;">Follow us on:&nbsp;&nbsp;<a class="socialIconLink cloverlinks" style="font-size: 1.875em; color: rgb(94, 111, 224);" href="https://www.facebook.com/ConnectionMillen" data-location="external" data-button="false" data-detail="https://www.facebook.com/ConnectionMillen" data-category="link" target="_blank"><span data-icon="facebook"><span class="clovericons fab" aria-hidden="true"></span><span class="sr-only">Facebook</span></span></a><a class="socialIconLink cloverlinks" href="https://www.facebook.com/ConnectionMillen" data-location="external" data-button="false" data-detail="https://www.facebook.com/ConnectionMillen" data-category="link" target="_blank" style="font-size: 1.0638em; letter-spacing: 0.01em; background-color: rgb(80, 80, 80);"> </a>&nbsp;&nbsp;<a class="socialIconLink cloverlinks" href="https://www.instagram.com/connectionchurchmillen/" data-location="external" data-button="false" data-detail="https://www.instagram.com/connectionchurchmillen/" data-category="link" target="_blank" style="letter-spacing: 0.01em; background-color: rgb(80, 80, 80); font-size: 1.875em;"><span data-icon="instagram"><span class="clovericons fab" style="color: rgb(214, 46, 4); font-size: 1em;" aria-hidden="true"></span><span class="sr-only">Instagram</span></span> </a> <a class="socialIconLink cloverlinks" style="color: rgb(250, 3, 3); font-size: 1.625em;" href="https://www.youtube.com/@ConnectionChurchMillen" data-location="external" data-button="false" data-detail="https://www.youtube.com/@ConnectionChurchMillen" data-category="link" target="_blank"><span data-socialicon="youtube"><span class="socialIconSymbol" aria-hidden="true"></span><span class="sr-only">youtube</span></span></a></p><p style="text-align: center; font-size: 1.0638em;">&nbsp; &nbsp; &nbsp;&nbsp;<br></p><p>Connect with us:&nbsp;</p><div><p><span class="clovercustom" style="font-size: 10pt; font-weight: 700;">Church Office Address</span></p><p><span class="clovercustom" style="font-size: 10pt;">1178 E. Winthrope Ave. Millen, GA 30442</span></p><p style="font-weight: 500; font-size: 0.9973em;"><br></p><p style="font-weight: 500; font-size: 0.9973em;">Sunday Service</p><p style="font-weight: 300;">Located in the cafeteria behind Jenkins County High School off North Ave.&nbsp;</p><p style="font-weight: 300;"><a href="https://maps.app.goo.gl/Sb33XGmMfvqtsSAt5" data-location="external" data-button="true" data-detail="https://maps.app.goo.gl/Sb33XGmMfvqtsSAt5" data-category="link" target="_blank" class="cloverlinks sites-button" role="button">Click Here for Directions</a></p><p><span class="clovercustom" style="font-size: 10pt;"><br></span></p></div></div></div>`,
  //prettier-ignore
  entry: { ...entry, selector: "[data-id=\"12866076\"]" },
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
                text: '<p class="brz-fs-lg-1_06 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span>Follow us on:&nbsp;&nbsp;</span>&nbsp;&nbsp; </p>'
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
                name: "facebook",
                type: "fa",
                linkExternal: "https://www.facebook.com/ConnectionMillen",
                linkType: "external",
                linkExternalBlank: "on"
              }
            },
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                padding: 7,
                bgColorHex: "#505050",
                bgColorOpacity: undefined,
                bgColorPalette: "",
                colorHex: "#d62e04",
                colorOpacity: 1,
                colorPalette: "",
                hoverColorHex: "#d62e04",
                hoverColorOpacity: 0.8,
                hoverColorPalette: "",
                customSize: 26,
                name: "instagram",
                type: "fa",
                linkExternal:
                  "https://www.instagram.com/connectionchurchmillen/",
                linkType: "external",
                linkExternalBlank: "on"
              }
            },
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                padding: 7,
                customSize: 26,
                name: "youtube",
                type: "fa",
                linkExternal: "https://www.youtube.com/@ConnectionChurchMillen",
                linkType: "external",
                linkExternalBlank: "on"
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
                text: `<p class="brz-fs-lg-1_06 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0">&nbsp; &nbsp; &nbsp;&nbsp;<br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Connect with us:&nbsp;</span></p><p class="brz-fs-lg-10 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 700; ">Church Office Address</span></p><p class="brz-fs-lg-10 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="">1178 E. Winthrope Ave. Millen, GA 30442</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-500 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-500 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 500; ">Sunday Service</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 300; ">Located in the cafeteria behind Jenkins County High School off North Ave.&nbsp;</span></p>`
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
                text: "Click Here for Directions",
                borderStyle: "none",
                fontFamily: "lato",
                fontFamilyType: "upload",
                fontStyle: "",
                lineHeight: 1.3,
                mobileFontStyle: "",
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontStyle: "",
                tabletLineHeight: 1.2,
                linkExternal: "https://maps.app.goo.gl/Sb33XGmMfvqtsSAt5",
                linkType: "external",
                linkExternalBlank: "on",
                iconName: ""
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style=""><br></span></p>'
              }
            }
          ]
        }
      }
    ]
  }
};
//#endregion

//#region Example 15 (Text with nested nodes)
const ex15: Data = {
  html: `<div class="text-content text-1 editable" data-id="24610452" data-category="text"><div><p style="text-align: center; font-size: 2.1277em; font-weight: 500;"><u>Who's your one for 2024?</u></p><div dir="auto" style="text-align: center; font-size: 2.1277em; font-weight: 500;">Who's the one you are praying for this year to know Jesus?<div dir="auto">Who's the one you are investing in this year?<div dir="auto">Who's the one you are allowing to invest in you this year?</div><div dir="auto"><br></div><div dir="auto" style="font-size: 0.8125em; font-style: italic;">If you would like to tell us stories of your journey, we'd love to hear them!!&nbsp; <a href="https://docs.google.com/forms/d/1_AX6wHW5zGDd6082vCXVTC2QQbmFufL8ABmFqdQKU6Q/edit#settings" data-location="external" data-button="false" data-detail="https://docs.google.com/forms/d/1_AX6wHW5zGDd6082vCXVTC2QQbmFufL8ABmFqdQKU6Q/edit#settings" data-category="link" target="_blank" class="cloverlinks">Click Here</a> to share.</div><div dir="auto" style="font-size: 0.8125em; font-style: italic;">If you need some help finding someone to invest in or to invest in you, <a href="https://docs.google.com/forms/d/1LA-3CfWM4edzYng-vC26G5cNWddGtuUVAXkiYCAoWm8/edit" data-location="external" data-button="false" data-detail="https://docs.google.com/forms/d/1LA-3CfWM4edzYng-vC26G5cNWddGtuUVAXkiYCAoWm8/edit" data-category="link" target="_blank" class="cloverlinks">click here</a> and let us walk with you and help you on your journey.</div></div></div></div></div>`,
  //prettier-ignore
  entry: { ...entry, selector: "[data-id=\"24610452\"]" },
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
                text: '<p class="brz-fs-lg-2_13 brz-ff-lato brz-ft-upload brz-fw-lg-500 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><u><span>Who\'s your one for 2024?</span></u></p><p class="brz-fs-lg-2_13 brz-ff-lato brz-ft-upload brz-fw-lg-500 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span style="font-weight: 500; ">Who\'s the one you are praying for this year to know Jesus?</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Who\'s the one you are investing in this year?</span></p><p dir="auto" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Who\'s the one you are allowing to invest in you this year?</span></p><p dir="auto" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p dir="auto" class="brz-fs-lg-0_81 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><em>If you would like to tell us stories of your journey, we\'d love to hear them!!&nbsp; </em><a data-location="external" data-button="false" data-detail="https://docs.google.com/forms/d/1_AX6wHW5zGDd6082vCXVTC2QQbmFufL8ABmFqdQKU6Q/edit#settings" data-category="link" target="_blank" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fdocs.google.com%2Fforms%2Fd%2F1_AX6wHW5zGDd6082vCXVTC2QQbmFufL8ABmFqdQKU6Q%2Fedit%23settings%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span>Click Here</span></a><em> to share.</em></p><p dir="auto" class="brz-fs-lg-0_81 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><em>If you need some help finding someone to invest in or to invest in you, </em><a data-location="external" data-button="false" data-detail="https://docs.google.com/forms/d/1LA-3CfWM4edzYng-vC26G5cNWddGtuUVAXkiYCAoWm8/edit" data-category="link" target="_blank" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fdocs.google.com%2Fforms%2Fd%2F1LA-3CfWM4edzYng-vC26G5cNWddGtuUVAXkiYCAoWm8%2Fedit%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span>click here</span></a><em> and let us walk with you and help you on your journey.</em></p>'
              }
            }
          ]
        }
      }
    ]
  }
};
//#endregion

//#region Example 16 (Adjust Mobile and Tabled device, line-height)
const ex16: Data = {
  html: `<div class="text-content text-1 editable" data-id="13239711" data-category="text"><div><p style="color: rgb(2, 78, 105);"><span class="clovercustom">Why do we give to the local church?</span></p><p style="color: rgb(2, 78, 105);"><br></p><p style="color: rgb(2, 78, 105);"><style></style></p><p style="font-size: 1.3298em; color: rgb(2, 78, 105);">Jesus said that where your treasure is, your heart is there also. What He means is that if our money is our treasure, our heart belongs to our wallets and not to Him. The Bible also speaks about being a good steward of all the blessings God has given you, including your money. So we want to honor Him by giving back a portion of what He has so generously given to us.</p></div></div>`,
  //prettier-ignore
  entry: { ...entry, selector: "[data-id=\"13239711\"]" },
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
                text: `<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">Why do we give to the local church?</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">Jesus said that where your treasure is, your heart is there also. What He means is that if our money is our treasure, our heart belongs to our wallets and not to Him. The Bible also speaks about being a good steward of all the blessings God has given you, including your money. So we want to honor Him by giving back a portion of what He has so generously given to us.</span></p>`
              }
            }
          ]
        }
      }
    ]
  }
};
//#endregion

//#region Example 17 (Calculate line-height from em to px)

const ex17: Data = {
  html: `<div class="text-content text-1 editable" data-id="13239755" data-category="text"><div><p style="color: rgb(2, 78, 105);"><span lang="EN-US" class="clovercustom" style="font-family: &quot;League Spartan&quot;, sans-serif; font-weight: 400; font-size: 1.9947em;">Why do we give to the local church?</span></p><p style="color: rgb(2, 78, 105);"><br></p><p style="color: rgb(2, 78, 105);"><style></style></p><p style="font-size: 1.3298em; color: rgb(2, 78, 105);">Jesus said that where your treasure is, your heart is there also. What He means is that if our money is our treasure, our heart belongs to our wallets and not to Him. The Bible also speaks about being a good steward of all the blessings God has given you, including your money. So we want to honor Him by giving back a portion of what He has so generously given to us.</p></div></div>`,
  //prettier-ignore
  entry: { ...entry, selector: "[data-id=\"13239755\"]" },
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
                text: '<p class="brz-fs-lg-1_99 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span lang="EN-US" style="font-weight: 400; color: rgb(2, 78, 105); ">Why do we give to the local church?</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">Jesus said that where your treasure is, your heart is there also. What He means is that if our money is our treasure, our heart belongs to our wallets and not to Him. The Bible also speaks about being a good steward of all the blessings God has given you, including your money. So we want to honor Him by giving back a portion of what He has so generously given to us.</span></p>'
              }
            }
          ]
        }
      }
    ]
  }
};

//#endregion

//#region Example 18 (Fixed the space between words)

const ex18: Data = {
  html: `<div class="text-content text-1 editable" data-id="13228982" data-category="text"><div><p>








<style>

</style>






</p><p>








<style>

</style>






</p><p style="font-size: 1.6622em; color: rgb(2, 78, 105);"><span class="clovercustom" style="font-family: &quot;League Spartan&quot;, sans-serif; font-weight: 400;">THE
INERRANCY OF THE BIBLE</span></p><p style="font-size: 1.3298em; color: rgb(2, 78, 105);"><br></p><p style="font-size: 1.3298em; color: rgb(2, 78, 105);">The scriptures, which include the Old and New
Testaments, are divinely inspired and represent the infallible and
authoritative word of God in all matters of faith and conduct. (2 Timothy
3:15-17; 1 Thessalonians 2:13; 2 Peter 1:21)<br></p><p style="font-size: 1.3298em; color: rgb(2, 78, 105);"><br></p><p style="font-size: 1.6622em; color: rgb(2, 78, 105);"><span class="clovercustom" style="font-family: &quot;League Spartan&quot;, sans-serif; font-weight: 400;">BELIEF IN
THE ONE TRUE GOD</span></p><p style="color: rgb(2, 78, 105);"><br></p><p style="font-size: 1.3298em; color: rgb(2, 78, 105);">There is
one eternal God, existent in three persons- God the Father, God the Son, and
God the Holy Spirit. (Deuteronomy 6:4; Isaiah 43:10-11; Matthew 28:19; Luke
3:22)</p><p style="font-size: 1.3298em; color: rgb(2, 78, 105);"><br></p><p style="color: rgb(2, 78, 105);"><span lang="EN-US" class="clovercustom" style="font-family: &quot;League Spartan&quot;, sans-serif; font-weight: 400; font-size: 1.6622em;">JESUS
CHRIST IS FULLY GOD AND FULLY MAN</span></p><p style="font-size: 1.3298em; color: rgb(2, 78, 105);"><br></p><p style="font-size: 1.3298em; color: rgb(2, 78, 105);">The Lord
Jesus Christ is the eternal Son of God. Born of a virgin. (Matthew 1:23; Luke
1:31-35) Lived a sinless life. (Hebrews 7:26; 1 Peter 2:22; Hebrews 4:15) Died
on the cross to atone for our sins. (1 Corinthians 15:3; 2 Corinthians 5:21)
Physically rose from the dead. (Acts 1:9; Acts 2:32; Hebrews 1:3) Now sits at
the right hand of His Father. (Acts 2:33; Hebrews 10:12) He will return to
earth in power and glory (Zechariah 14:5; Matthew 24:27; Revelation 19:11-14)</p><p style="font-size: 1.3298em; color: rgb(2, 78, 105);"><br></p><p style="font-family: &quot;League Spartan&quot;, sans-serif; font-weight: 400; font-size: 1.6622em; color: rgb(2, 78, 105);">SALVATION</p><p style="color: rgb(2, 78, 105);"><br></p><p style="font-size: 1.3298em; color: rgb(2, 78, 105);">Man's only
hope of redemption is through the shed blood of Jesus Christ. We are saved by
grace through faith when we accept Jesus Christ as our Lord and Savior, and we
are cleansed from sin through repentance and regeneration of the Holy Spirit.
(John 3:3; Luke 24:46-47; Romans 10:13; Ephesians 2:8; Titus 3:5-7; John 6:63)</p><p style="font-size: 1.3298em; color: rgb(2, 78, 105);"><br></p><p style="font-family: &quot;League Spartan&quot;, sans-serif; font-weight: 400; font-size: 1.6622em; color: rgb(2, 78, 105);">HOLY SPIRIT</p><p style="font-size: 1.3298em; color: rgb(2, 78, 105);"><br></p><p style="font-size: 1.3298em; color: rgb(2, 78, 105);">The Holy
Spirit, our abiding Helper, Teacher and Guide, indwells and empowers every
believer in Jesus Christ. (Romans 8:11; Romans 8:26-27; John 14:16-17; Isaiah
61; Acts 1:8; 1 Corinthians 2:10)</p><p style="font-size: 1.3298em; color: rgb(2, 78, 105);"><br></p><p style="font-family: &quot;League Spartan&quot;, sans-serif; font-weight: 400; font-size: 1.6622em; color: rgb(2, 78, 105);">THE CHURCH</p><p style="font-size: 1.3298em; color: rgb(2, 78, 105);"><br></p><p style="font-size: 1.3298em; color: rgb(2, 78, 105);">We believe
the church is the body of Christ, the fellowship of all believers. Christians
have been called out from the world to obey the teachings of Christ and serve
as His ambassadors and co-laborers. (Matthew 18:18-20; John 15:19-20; Ephesians
1:22-23; Ephesians 5:23; 1 Corinthians 3:9; 2 Corinthians 5:20)</p><p style="font-size: 1.3298em; color: rgb(2, 78, 105);"><br></p><p style="font-family: &quot;League Spartan&quot;, sans-serif; font-weight: 400; font-size: 1.6622em; color: rgb(2, 78, 105);">BAPTISM</p><p style="font-size: 1.3298em; color: rgb(2, 78, 105);"><br></p><p style="font-size: 1.3298em; color: rgb(2, 78, 105);">While we
recognize that the act of Baptism is observed in varying ways amongst different
fellowships, it is our conviction and practice to baptize new believers by
submersion. (Matthew 28:19; Acts 10:47)</p><p style="font-size: 1.3298em; color: rgb(2, 78, 105);"><br></p><p style="font-family: &quot;League Spartan&quot;, sans-serif; font-weight: 400; font-size: 1.6622em; color: rgb(2, 78, 105);">HOLY
COMMUNION</p><p style="font-size: 1.3298em; color: rgb(2, 78, 105);"><br></p><p style="font-size: 1.3298em; color: rgb(2, 78, 105);">The Lord’s
Supper is a symbolic act of obedience in which we partake of the bread and the
fruit of the vine, remembering the death of Jesus and anticipating His second
coming. (1 Corinthians 11:23-26)</p><p style="font-size: 1.3298em; color: rgb(2, 78, 105);"><br></p><p style="font-family: &quot;League Spartan&quot;, sans-serif; font-weight: 400; font-size: 1.6622em; color: rgb(2, 78, 105);">ETERNITY</p><p style="font-size: 1.3298em; color: rgb(2, 78, 105);"><br></p><p style="font-size: 1.3298em; color: rgb(2, 78, 105);">There will
be a final judgment and the resurrection of both the saved and the lost; one to
everlasting life and the other to everlasting damnation. (Matthew 25:46; Mark
9:43-48; Revelation 19:20, Revelation 21:8)</p><p><style>

</style></p></div></div>`,
  //prettier-ignore
  entry: { ...entry, selector: "[data-id=\"13228982\"]" },
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
                text: '<p class="brz-fs-lg-1_66 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 400; color: rgb(2, 78, 105); ">THE INERRANCY OF THE BIBLE</span></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">The scriptures, which include the Old and New Testaments, are divinely inspired and represent the infallible and authoritative word of God in all matters of faith and conduct. (2 Timothy 3:15-17; 1 Thessalonians 2:13; 2 Peter 1:21)</span><br></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_66 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 400; color: rgb(2, 78, 105); ">BELIEF IN THE ONE TRUE GOD</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">There is one eternal God, existent in three persons- God the Father, God the Son, and God the Holy Spirit. (Deuteronomy 6:4; Isaiah 43:10-11; Matthew 28:19; Luke 3:22)</span></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_66 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span lang="EN-US" style="font-weight: 400; color: rgb(2, 78, 105); ">JESUS CHRIST IS FULLY GOD AND FULLY MAN</span></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">The Lord Jesus Christ is the eternal Son of God. Born of a virgin. (Matthew 1:23; Luke 1:31-35) Lived a sinless life. (Hebrews 7:26; 1 Peter 2:22; Hebrews 4:15) Died on the cross to atone for our sins. (1 Corinthians 15:3; 2 Corinthians 5:21) Physically rose from the dead. (Acts 1:9; Acts 2:32; Hebrews 1:3) Now sits at the right hand of His Father. (Acts 2:33; Hebrews 10:12) He will return to earth in power and glory (Zechariah 14:5; Matthew 24:27; Revelation 19:11-14)</span></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_66 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); font-weight: 400; ">SALVATION</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">Man\'s only hope of redemption is through the shed blood of Jesus Christ. We are saved by grace through faith when we accept Jesus Christ as our Lord and Savior, and we are cleansed from sin through repentance and regeneration of the Holy Spirit. (John 3:3; Luke 24:46-47; Romans 10:13; Ephesians 2:8; Titus 3:5-7; John 6:63)</span></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_66 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); font-weight: 400; ">HOLY SPIRIT</span></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">The Holy Spirit, our abiding Helper, Teacher and Guide, indwells and empowers every believer in Jesus Christ. (Romans 8:11; Romans 8:26-27; John 14:16-17; Isaiah 61; Acts 1:8; 1 Corinthians 2:10)</span></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_66 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); font-weight: 400; ">THE CHURCH</span></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">We believe the church is the body of Christ, the fellowship of all believers. Christians have been called out from the world to obey the teachings of Christ and serve as His ambassadors and co-laborers. (Matthew 18:18-20; John 15:19-20; Ephesians 1:22-23; Ephesians 5:23; 1 Corinthians 3:9; 2 Corinthians 5:20)</span></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_66 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); font-weight: 400; ">BAPTISM</span></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">While we recognize that the act of Baptism is observed in varying ways amongst different fellowships, it is our conviction and practice to baptize new believers by submersion. (Matthew 28:19; Acts 10:47)</span></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_66 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); font-weight: 400; ">HOLY COMMUNION</span></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">The Lord’s Supper is a symbolic act of obedience in which we partake of the bread and the fruit of the vine, remembering the death of Jesus and anticipating His second coming. (1 Corinthians 11:23-26)</span></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_66 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); font-weight: 400; ">ETERNITY</span></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">There will be a final judgment and the resurrection of both the saved and the lost; one to everlasting life and the other to everlasting damnation. (Matthew 25:46; Mark 9:43-48; Revelation 19:20, Revelation 21:8)</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"></p>'
              }
            }
          ]
        }
      }
    ]
  }
};

//#endregion

//#region Example 19 (Button surrounded by text)

const ex19: Data = {
  html: `<div class="text-content text-1 editable" data-id="23300332" data-category="text"><div><p style="text-align: justify;"><span class="clovercustom" style="font-size: 1rem; letter-spacing: normal;">Hello and welcome to Connection Church Millen! </span><span class="clovercustom" style="font-size: 16px; letter-spacing: normal;">&nbsp;</span><span class="clovercustom" style="font-size: 1rem; letter-spacing: normal;">We are so glad that you have joined us.&nbsp; If you are new here, we would like to thank you for coming and worshiping with us. If you haven't filled out one of our Connect Cards yet, we would really</span><span style="font-size: 16px; letter-spacing: normal;"> love to learn a little more about you!</span><span class="clovercustom" style="font-size: 16px; letter-spacing: normal;">&nbsp;</span><span style="font-size: 1rem; letter-spacing: normal;">. If you would please follow this link <a style="font-size: 0.9375em;" href="/contact-us/first-time-guest" data-location="existing" data-button="true" data-detail="891913" data-category="link" target="_blank" class="cloverlinks sites-button" role="button">HERE.</a> We 'd love to find out what brought you here and what your experience was like.&nbsp; As well, every Sunday morning we have our Next Steps table available to find out about what YOUR next steps may be as a follower of Christ.</span><span style="font-size: 1rem; letter-spacing: normal;"> Thanks again for joining us and please make sure you follow us online on Facebook and Instagram (see links at bottom of page) and visit here often for all the latest info. Keep scrolling below to see all of our current announcements.</span></p></div></div>`,
  //prettier-ignore
  entry: { ...entry, selector: "[data-id=\"23300332\"]" },
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-justify brz-ls-lg-0"><span style="">Hello and welcome to Connection Church Millen! </span><span style="">&nbsp;</span><span style="">We are so glad that you have joined us.&nbsp; If you are new here, we would like to thank you for coming and worshiping with us. If you haven\'t filled out one of our Connect Cards yet, we would really</span><span style=""> love to learn a little more about you!</span><span style="">&nbsp;</span><span style="">. If you would please follow this link <a style="" data-location="existing" data-button="true" data-detail="891913" data-category="link" target="_blank" role="button" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22%2Fcontact-us%2Ffirst-time-guest%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span>HERE.</span></a> We \'d love to find out what brought you here and what your experience was like.&nbsp; As well, every Sunday morning we have our Next Steps table available to find out about what YOUR next steps may be as a follower of Christ.</span><span style=""> Thanks again for joining us and please make sure you follow us online on Facebook and Instagram (see links at bottom of page) and visit here often for all the latest info. Keep scrolling below to see all of our current announcements.</span></p>'
              }
            }
          ]
        }
      }
    ]
  }
};

//#endregion

//#region Example 20 (Check embedded code)

const ex20: Data = {
  html: `<div class="text-content text-1 editable" data-id="16715709" data-category="text"><div><div class="embedded-paste" contenteditable="false" data-src="<div style=&quot;position: relative; padding-bottom: 56.25%; height: 0;&quot;><iframe src=&quot;https://c.streamhoster.com/embed/list/WwsdHp/9bUVcw1smfm/qbfVoIpsOkx_2_1&quot; style=&quot;position: absolute; top: 0; left: 0; width: 100%; height: 100%;&quot; frameborder=&quot;0&quot; scrolling=&quot;no&quot; webkitallowfullscreen=&quot;&quot; mozallowfullscreen=&quot;&quot; allowfullscreen=&quot;&quot; allow=&quot;autoplay; fullscreen;&quot;></iframe></div>"><div style="position: relative; padding-bottom: 56.25%; height: 0;"><iframe src="https://c.streamhoster.com/embed/list/WwsdHp/9bUVcw1smfm/qbfVoIpsOkx_2_1" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" frameborder="0" scrolling="no" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen="" allow="autoplay; fullscreen;"></iframe></div></div></div></div>`,
  //prettier-ignore
  entry: { ...entry, selector: "[data-id=\"16715709\"]" },
  output: {
    data: [
      {
        type: "EmbedCode"
      }
    ]
  }
};

//#endregion

//#region Example 21 (Check embedded code and richtext)

const ex21: Data = {
  html: `<div class="text-content text-1 editable" data-id="18004075" data-category="text"><div><p>We're pleased to welcome you to the Antioch Christian Church LIVE Stream page. We hope you enjoy our services and special occasions from wherever you are. Whenever possible, we hope you take the time to join us in person for our live in-person services.</p><p><br></p><p><br></p><div class="embedded-paste" contenteditable="false" data-src="<div style=&quot;position: relative; padding-bottom: 56.25%; height: 0;&quot;><iframe src=&quot;https://c.streamhoster.com/embed/media/WwsdHp/9bUVcw1smfm/iim6hLsYxHA_5&quot; style=&quot;position: absolute; top: 0; left: 0; width: 100%; height: 100%;&quot; frameborder=&quot;0&quot; scrolling=&quot;no&quot; webkitallowfullscreen=&quot;&quot; mozallowfullscreen=&quot;&quot; allowfullscreen=&quot;&quot; allow=&quot;autoplay; fullscreen;&quot;></iframe></div>"><div style="position: relative; padding-bottom: 56.25%; height: 0;"><iframe src="https://c.streamhoster.com/embed/media/WwsdHp/9bUVcw1smfm/iim6hLsYxHA_5" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" frameborder="0" scrolling="no" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen="" allow="autoplay; fullscreen;"></iframe></div></div></div></div>`,
  //prettier-ignore
  entry: { ...entry, selector: "[data-id=\"18004075\"]" },
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>We\'re pleased to welcome you to the Antioch Christian Church LIVE Stream page. We hope you enjoy our services and special occasions from wherever you are. Whenever possible, we hope you take the time to join us in person for our live in-person services.</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p>'
              }
            }
          ]
        }
      },
      {
        type: "EmbedCode"
      }
    ]
  }
};

//#endregion

//#region Example 22 (Wrap Link inside P tag)

const ex22: Data = {
  html: `<div class="text-content text-0 editable" data-id="24125822" data-category="text"><div><div><p style="text-align: center; font-family: &quot;League Spartan&quot;, sans-serif; font-weight: 400;"><a style="font-size: 2.1875em; font-family: &quot;League Spartan&quot;, sans-serif; font-weight: 400;" href="https://www.tiktok.com/@friendshipbaptisthp?lang=en" data-location="external" data-button="false" data-detail="https://www.tiktok.com/@friendshipbaptisthp?lang=en" data-category="link" target="_blank" class="cloverlinks">TikTok</a></p></div></div></div>`,
  //prettier-ignore
  entry: { ...entry, selector: "[data-id=\"24125822\"]" },
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><a style="font-weight: 400; " data-location="external" data-button="false" data-detail="https://www.tiktok.com/@friendshipbaptisthp?lang=en" data-category="link" target="_blank" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fwww.tiktok.com%2F%40friendshipbaptisthp%3Flang%3Den%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span style="font-weight: 400; ">TikTok</span></a></p>'
              }
            }
          ]
        }
      }
    ]
  }
};
//#endregion

//#region Example 23 (Button structure 1)
const ex23: Data = {
  html: `<a href="/about-us/who-we-are" data-category="link" data-location="existing" data-detail="1313014" data-url="/about-us/who-we-are"><button class="sites-button editable" data-id="24286133" data-category="button" tabindex="-1"><div class="sites-button-text">More</div></button></a>`,
  //prettier-ignore
  entry: { ...entry, selector: "[data-id=\"24286133\"]" },
  output: {
    data: [
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
                text: "More",
                borderStyle: "none",
                fontFamily: "lato",
                fontFamilyType: "upload",
                fontStyle: "",
                lineHeight: 1.3,
                mobileFontStyle: "",
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontStyle: "",
                tabletLineHeight: 1.2,
                linkExternal: "/about-us/who-we-are",
                linkType: "external",
                linkExternalBlank: "on",
                iconName: ""
              }
            }
          ],
          horizontalAlign: "center"
        }
      }
    ]
  }
};
//#endregion

//#region Example 24 (Voyage button border)
const ex24: Data = {
  html: `<div class="text-content text-1 editable" data-id="20010041" data-category="text"><div><p><br></p><p style="font-size: 1.0013em; color: rgb(0, 0, 0);"><span class="clovercustom" style="font-weight: 600;">Timberwood Church is a Bible-based church designed to help people experience </span><span class="clovercustom" style="font-weight: 600;">God’s goodness in their lives.</span>&nbsp;</p><p style="font-size: 1.0013em; color: rgb(0, 0, 0);">Through teaching and contemporary musical worship, we seek to make the timeless truth of the Bible relevant for everyday life.&nbsp;</p><p style="font-size: 1.0013em; color: rgb(0, 0, 0);">The purpose of Timberwood Church is to honor God by making more disciples for Jesus Christ.</p><p style="font-size: 1.1264em; font-weight: 600; line-height: 1.625;"><br></p><p style="font-weight: 600; line-height: 1.625; font-size: 1.5019em;"><span class="clovercustom" style="font-size: 1.1666em; font-style: italic; color: rgb(105, 142, 179);">We invite you to worship, serve, and celebrate with us!</span><br><span class="clovercustom" style="color: rgb(0, 0, 0);">Sunday services are at 9 a.m. and 10:30 a.m.</span></p><p style="font-size: 1.1264em; font-weight: 600; line-height: 1.625;"><br></p><p style="font-size: 1.1264em; font-weight: 600; line-height: 1.625;"><a href="https://www.google.com/maps/d/edit?mid=1q-aNw687DnrS5vW9y0EE1SBnydw&amp;usp=sharing" class="sites-button cloverlinks" role="button" data-location="external" data-detail="https://www.google.com/maps/d/edit?mid=1q-aNw687DnrS5vW9y0EE1SBnydw&amp;usp=sharing" data-category="button" target="_blank">Visit our Prayer Path</a> </p></div></div>`,
  //prettier-ignore
  entry: { ...entry, selector: "[data-id=\"20010041\"]" },
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
                text: `<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 600; color: rgb(0, 0, 0); ">Timberwood Church is a Bible-based church designed to help people experience </span><span style="font-weight: 600; color: rgb(0, 0, 0); ">God’s goodness in their lives.</span>&nbsp;</p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(0, 0, 0); ">Through teaching and contemporary musical worship, we seek to make the timeless truth of the Bible relevant for everyday life.&nbsp;</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(0, 0, 0); ">The purpose of Timberwood Church is to honor God by making more disciples for Jesus Christ.</span></p><p class="brz-fs-lg-1_13 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_17 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><em style="color: rgb(105, 142, 179); font-weight: 600; ">We invite you to worship, serve, and celebrate with us!</em><br><span style="color: rgb(0, 0, 0); font-weight: 600; ">Sunday services are at 9 a.m. and 10:30 a.m.</span></p><p class="brz-fs-lg-1_13 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p>`
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
          horizontalAlign: "center",
          items: [
            {
              type: "Button",
              value: {
                _id: "1",
                _styles: ["button"],
                borderStyle: "none",
                fontFamily: "lato",
                fontFamilyType: "upload",
                fontStyle: "",
                lineHeight: 1.3,
                mobileFontStyle: "",
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontStyle: "",
                tabletLineHeight: 1.2,
                linkExternal:
                  "https://www.google.com/maps/d/edit?mid=1q-aNw687DnrS5vW9y0EE1SBnydw&usp=sharing",
                linkExternalBlank: "on",
                linkType: "external",
                text: "Visit our Prayer Path",
                iconName: ""
              }
            }
          ]
        }
      }
    ]
  }
};
//#endregion

//#region Example 25 (Voyage button border)
const ex25: Data = {
  html: `<div class="text-content text-1 editable" data-id="25409950" data-category="text" id="accordion-25409945-body" role="region" aria-labelledby="accordion-25409945-title"><div><p><a href="https://f4c1f8a9.churchtrac.com/connect" class="sites-button cloverlinks" role="button" data-location="external" data-detail="https://f4c1f8a9.churchtrac.com/connect" data-category="button" target="_blank">make a donation</a><br></p></div></div>`,
  //prettier-ignore
  entry: { ...entry, selector: "[data-id=\"25409950\"]" },
  output: {
    data: [
      {
        type: "Cloneable",
        value: {
          _id: "1",
          _styles: ["wrapper-clone", "wrapper-clone--button"],
          horizontalAlign: "center",
          items: [
            {
              type: "Button",
              value: {
                _id: "1",
                _styles: ["button"],
                borderStyle: "none",
                fontFamily: "lato",
                fontFamilyType: "upload",
                fontStyle: "",
                lineHeight: 1.3,
                mobileFontStyle: "",
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontStyle: "",
                tabletLineHeight: 1.2,
                linkExternal: "https://f4c1f8a9.churchtrac.com/connect",
                linkExternalBlank: "on",
                linkType: "external",
                text: "make a donation",
                iconName: ""
              }
            }
          ]
        }
      }
    ]
  }
};
//#endregion

//#region Example 26 (Bloom text with button and link)
const ex26: Data = {
  html: `<div class="text-content text-1 editable" data-id="25409950" data-category="text" id="accordion-25409945-body" role="region" aria-labelledby="accordion-25409945-title"><div><p>Once there, click on “Sign In” in the upper right corner. From there you can either sign into your existing account or create a new account. When creating a new account, it may take a few days for our treasurer to activate your account.</p><p><br></p><p><a href="https://f4c1f8a9.churchtrac.com/connect" class="sites-button cloverlinks" role="button" data-location="external" data-detail="https://f4c1f8a9.churchtrac.com/connect" data-category="button" target="_blank">make a donation</a><br></p><p><br></p><p>Please contact our Administrator at <a href="mailto:kchristian@rockhavenchurch.org" data-location="email" data-button="false" data-detail="kchristian@rockhavenchurch.org" data-category="link" target="_self" class="cloverlinks">kchristian@rockhavenchurch.org</a> if you need assistance.&nbsp;</p><p></p></div></div>`,
  //prettier-ignore
  entry: { ...entry, selector: "[data-id=\"25409950\"]" },
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Once there, click on “Sign In” in the upper right corner. From there you can either sign into your existing account or create a new account. When creating a new account, it may take a few days for our treasurer to activate your account.</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p>'
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
          horizontalAlign: "center",
          items: [
            {
              type: "Button",
              value: {
                _id: "1",
                _styles: ["button"],
                borderStyle: "none",
                fontFamily: "lato",
                fontFamilyType: "upload",
                fontStyle: "",
                lineHeight: 1.3,
                linkExternal: "https://f4c1f8a9.churchtrac.com/connect",
                linkExternalBlank: "on",
                linkType: "external",
                mobileFontStyle: "",
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontStyle: "",
                tabletLineHeight: 1.2,
                text: "make a donation",
                iconName: ""
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Please contact our Administrator at </span><a data-location="email" data-button="false" data-detail="kchristian@rockhavenchurch.org" data-category="link" target="_self" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22mailto%3Akchristian%40rockhavenchurch.org%22%2C%22externalBlank%22%3A%22off%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span>kchristian@rockhavenchurch.org</span></a><span> if you need assistance.&nbsp;</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"></p>'
              }
            }
          ]
        }
      }
    ]
  }
};
//#endregion

//#region Example 27 (Ember text with button )
const ex27: Data = {
  html: `<header class="text-content text-1 title-text editable" data-id="23478402" data-category="text"><div><p>BROADCAST VIDEO</p><p style="font-size: 0.625em;">Interested in joining this team?</p><p style="font-size: 0.625em;"><a href="https://forms.ministryforms.net/viewForm.aspx?formId=e6c65131-c32a-49ed-b8c6-e63634a7f0d2" data-location="external" data-button="true" data-detail="https://forms.ministryforms.net/viewForm.aspx?formId=e6c65131-c32a-49ed-b8c6-e63634a7f0d2" data-category="link" target="_blank" class="cloverlinks sites-button" role="button" style="font-size: 1.0667em;">CLICK HERE</a></p></div></header>`,
  //prettier-ignore
  entry: { ...entry, selector: "[data-id=\"23478402\"]" },
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>BROADCAST VIDEO</span></p><p class="brz-fs-lg-0_63 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Interested in joining this team?</span></p>'
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
          horizontalAlign: "center",
          items: [
            {
              type: "Button",
              value: {
                _id: "1",
                _styles: ["button"],
                borderStyle: "none",
                fontFamily: "lato",
                fontFamilyType: "upload",
                fontSize: 1,
                fontStyle: "",
                lineHeight: 1.3,
                linkExternal:
                  "https://forms.ministryforms.net/viewForm.aspx?formId=e6c65131-c32a-49ed-b8c6-e63634a7f0d2",
                linkExternalBlank: "on",
                linkType: "external",
                mobileFontSize: 1,
                mobileFontStyle: "",
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontSize: 1,
                tabletFontStyle: "",
                tabletLineHeight: 1.2,
                text: "CLICK HERE",
                iconName: ""
              }
            }
          ]
        }
      }
    ]
  }
};
//#endregion

//#region Example 28 (Ember text with link )
const ex28: Data = {
  html: `<<header class="text-content text-1 title-text editable" data-id="23478396" data-category="text"><div><p>PRE-SCHOOL</p><p style="font-size: 0.75em;"><span class="clovercustom" style="font-size: 0.8333em;">Interested In Joining This Team?</span><a href="https://forms.ministryforms.net/viewForm.aspx?formId=10095747-3f27-49ab-a7a7-567fca858af3" data-location="external" data-button="true" data-detail="https://forms.ministryforms.net/viewForm.aspx?formId=10095747-3f27-49ab-a7a7-567fca858af3" data-category="link" target="_blank" class="cloverlinks sites-button" role="button" style="background-color: rgb(255, 255, 255); font-size: 0.8889em;">CLICK HERE</a></p></div></header>`,
  //prettier-ignore
  entry: { ...entry, selector: "[data-id=\"23478396\"]" },
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>PRE-SCHOOL</span></p><p class="brz-fs-lg-0_83 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="">Interested In Joining This Team?</span><a data-location="external" data-button="true" data-detail="https://forms.ministryforms.net/viewForm.aspx?formId=10095747-3f27-49ab-a7a7-567fca858af3" data-category="link" target="_blank" role="button" style="background-color: rgb(255, 255, 255); " data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fforms.ministryforms.net%2FviewForm.aspx%3FformId%3D10095747-3f27-49ab-a7a7-567fca858af3%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span style="background-color: rgb(255, 255, 255); ">CLICK HERE</span></a></p>'
              }
            }
          ]
        }
      }
    ]
  }
};
//#endregion

//#region Example 29 (Bloom list)
const ex29: Data = {
  html: `<div class="text-content text-1 editable" data-id="23136535" data-category="text"><div><p style="text-align: left; font-weight: 600;"><strong style="font-size: 15px; letter-spacing: 0.45px;">MONTHLY PRAYER GATHERING</strong><br></p><div style="text-align: left; font-weight: 200;"><ul><li>Have you ever struggled praying out loud in front of other people? Consider joining us for our monthly prayer meeting as we seek to learn, encourage, and grow deeper with God through prayer together.&nbsp;</li><li>Our prayer meetings happen monthly on the second Tuesday of every month at 6pm.</li></ul></div></div></div>`,
  //prettier-ignore
  entry: { ...entry, selector: "[data-id=\"23136535\"]" },
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
                text: '<p class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-bold brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-0_4"><strong style="font-weight: bold; ">MONTHLY PRAYER GATHERING</strong><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-200 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"></p><ul><li class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Have you ever struggled praying out loud in front of other people? Consider joining us for our monthly prayer meeting as we seek to learn, encourage, and grow deeper with God through prayer together.&nbsp;</span></li><li class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Our prayer meetings happen monthly on the second Tuesday of every month at 6pm.</span></li></ul><p></p>'
              }
            }
          ]
        }
      }
    ]
  }
};
//#endregion

//#region Example 30 (Voyage Text with bold link)
const ex30: Data = {
  html: `<div class="text-content text-1 editable" data-id="25300675" data-category="text"><div><p style="line-height: 18pt;"><span class="clovercustom" style="font-size: 14pt;">Child dedication is
  an opportunity to acknowledge children as a gift from God. Families dedicating
  their child(ren) make the commitment, to raise them to know, love and serve the
  Lord according to His Word, the Bible. Timberwood Church, as a body of Christ,
  supports this commitment by loving children as Jesus did, providing an
  environment for children to experience the Holy Spirit and partnering with
  parents. If you would like more information about Child Dedication, contact
  Eric Holst at </span><a href="mailto:eric.holst@timberwoodchurch.org" target="_self" data-location="email" data-button="false" data-detail="eric.holst@timberwoodchurch.org" data-category="link"><b><span class="clovercustom" style="font-size: 14pt;">eric.holst@timberwoodchurch.org</span></b></a><span class="clovercustom" style="font-size: 15pt;"></span></p></div></div>`,
  //prettier-ignore
  entry: { ...entry, selector: "[data-id=\"25300675\"]" },
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
                text: '<p class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="">Child dedication is   an opportunity to acknowledge children as a gift from God. Families dedicating   their child(ren) make the commitment, to raise them to know, love and serve the   Lord according to His Word, the Bible. Timberwood Church, as a body of Christ,   supports this commitment by loving children as Jesus did, providing an   environment for children to experience the Holy Spirit and partnering with   parents. If you would like more information about Child Dedication, contact   Eric Holst at </span><a target="_self" data-location="email" data-button="false" data-detail="eric.holst@timberwoodchurch.org" data-category="link" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22mailto%3Aeric.holst%40timberwoodchurch.org%22%2C%22externalBlank%22%3A%22off%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><b><span style="">eric.holst@timberwoodchurch.org</span></b></a><span style=""></span></p>'
              }
            }
          ]
        }
      }
    ]
  }
};
//#endregion

//#region Example 31 (Anthem embed)
const ex31: Data = {
  html: `<div class="text-content text-1 editable" data-id="19657540" data-category="text"><div><div class="embedded-paste" contenteditable="false" data-src="<div id=&quot;resources_calendar_widget&quot; class=&quot;styled&quot;>
  <div class=&quot;loader&quot;>Loading...</div>
</div>
<script type=&quot;text/javascript&quot;>
  (function(){
    var rs = document.createElement(&quot;script&quot;);
    rs.type = &quot;text/javascript&quot;;
    rs.async = true;
    rs.src = &quot;//calendar.planningcenteronline.com/widgets/eJxj4ajmsGLLz2RulWe04kotzi8oqea0Yq9I81TiMjQ0NjZns2JzDbFiK_ZU4k_MyYlPLUvNKylms-YAipVmMs-SASouK_FU4gNJlmTmpkLkuAsSixJzi6sZAJFMGcs=64decca82d0dec7dd0874acece089d680844e71d.js&quot;;
    var s = document.getElementsByTagName(&quot;script&quot;)[0];
    s.parentNode.insertBefore(rs,s);
  })();
</script>"><div id="resources_calendar_widget" class="styled"><div class="rc-content rc-clearfix">   <div id="global_header">     <h2 class="rc-date ellipsis">       Friday, October 25, 2024     </h2>     <div class="rc-position-helper">       <div class="rc-kal_popover" style="display: none;">       <div class="kalendae"><div class="k-calendar" data-cal-index="0"><div class="k-title"><a class="k-btn-previous-year"></a><a class="k-btn-previous-month"></a><a class="k-btn-next-year"></a><a class="k-btn-next-month"></a><span class="k-caption">October, 2024</span></div><div class="k-header"><span>Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span></div><div class="k-days"><span class="k-out-of-month" data-date="2024-09-29">29</span><span class="k-out-of-month" data-date="2024-09-30">30</span><span class="k-in-month k-active" data-date="2024-10-01">1</span><span class="k-in-month k-active" data-date="2024-10-02">2</span><span class="k-in-month k-active" data-date="2024-10-03">3</span><span class="k-in-month k-active" data-date="2024-10-04">4</span><span class="k-in-month k-active" data-date="2024-10-05">5</span><span class="k-in-month k-active" data-date="2024-10-06">6</span><span class="k-in-month k-active" data-date="2024-10-07">7</span><span class="k-in-month k-active" data-date="2024-10-08">8</span><span class="k-in-month k-active" data-date="2024-10-09">9</span><span class="k-in-month k-active" data-date="2024-10-10">10</span><span class="k-in-month k-active" data-date="2024-10-11">11</span><span class="k-in-month k-active" data-date="2024-10-12">12</span><span class="k-in-month k-active" data-date="2024-10-13">13</span><span class="k-in-month k-active" data-date="2024-10-14">14</span><span class="k-in-month k-active" data-date="2024-10-15">15</span><span class="k-in-month k-active" data-date="2024-10-16">16</span><span class="k-in-month k-active" data-date="2024-10-17">17</span><span class="k-in-month k-active" data-date="2024-10-18">18</span><span class="k-in-month k-active" data-date="2024-10-19">19</span><span class="k-in-month k-active" data-date="2024-10-20">20</span><span class="k-in-month k-active" data-date="2024-10-21">21</span><span class="k-in-month k-active" data-date="2024-10-22">22</span><span class="k-in-month k-active" data-date="2024-10-23">23</span><span class="k-in-month k-active" data-date="2024-10-24">24</span><span class="k-selected k-in-month k-active k-today" data-date="2024-10-25">25</span><span class="k-in-month k-active" data-date="2024-10-26">26</span><span class="k-in-month k-active" data-date="2024-10-27">27</span><span class="k-in-month k-active" data-date="2024-10-28">28</span><span class="k-in-month k-active" data-date="2024-10-29">29</span><span class="k-in-month k-active" data-date="2024-10-30">30</span><span class="k-in-month k-active" data-date="2024-10-31">31</span><span class="k-out-of-month" data-date="2024-11-01">1</span><span class="k-out-of-month" data-date="2024-11-02">2</span><span class="k-out-of-month" data-date="2024-11-03">3</span><span class="k-out-of-month" data-date="2024-11-04">4</span><span class="k-out-of-month" data-date="2024-11-05">5</span><span class="k-out-of-month" data-date="2024-11-06">6</span><span class="k-out-of-month" data-date="2024-11-07">7</span><span class="k-out-of-month" data-date="2024-11-08">8</span><span class="k-out-of-month" data-date="2024-11-09">9</span></div></div></div></div>       <ul class="rc-navi">         <li class="rc-btn_wrap rc-disparate-btn">           <button class="rc-icon-date-btn rc-btn rc-btn_primary" onclick="PCOResourcesWidget.toggleKal(); return false;">             <span class="img-icon-date rc-icon">             </span>           </button>         </li>         <li class="rc-prev rc-btn_wrap">           <button class="rc-btn rc-btn_primary" onclick="PCOResourcesWidget.loadDataForDate('2024-10-24'); return false;">             Prev           </button></li>         <li class="rc-today rc-btn_wrap">           <button class="rc-btn rc-btn_primary" onclick="PCOResourcesWidget.loadDataForDate('2024-10-25'); return false;">             Today           </button></li>         <li class="rc-next rc-btn_wrap">           <button class="rc-btn rc-btn_primary" onclick="PCOResourcesWidget.loadDataForDate('2024-10-26'); return false;">             Next           </button></li>       </ul>     </div>   </div>   <div class="rc-resources_grid">     <table class="rc-table_striped">       <thead>         <tr>           <th class="rc-time_col">             <div class="rc-table_sleeve">               Time             </div>           </th>           <th class="rc-primary_col">             <div class="rc-table_sleeve">               Location             </div>           </th>           <th colspan="2">             <div class="rc-table_sleeve">               Event               <input id="search_input" placeholder="Filter..." type="text">             </div>           </th>         </tr>       </thead>       <tbody id="filter_body">         <tr class="no_results rc-row" style="display: none;">           <td class="rc-time rc-time_col">             <div class="rc-table_sleeve">               <span class="rc-results_placeholder">—</span>             </div>           </td>           <td class="rc-primary_col">             <div class="rc-table_sleeve">               <span class="rc-results_placeholder">—</span>             </div>           </td>           <td class="rc-event_title">             <div class="rc-table_sleeve">               <span class="rc-results_placeholder">—</span>             </div>           </td>           <td class="rc-event_time_name">             <div class="rc-table_sleeve">               <span class="rc-results_placeholder">—</span>             </div>           </td>         </tr>             <tr class="rc-row visible">               <td class="rc-time rc-time_col" style="white-space:nowrap;">                 <div class="rc-table_sleeve">                   10:00a - 11:45a                 </div>               </td>               <td class="rc-primary_col">                 <div class="rc-table_sleeve" style="overflow-wrap:anywhere" ;="">                   Boyd's Home - 2128 Thoroughbred Pkwy Goochland, VA 23063                 </div>               </td>               <td class="rc-event_title">                 <div class="rc-table_sleeve">                   Mornings with Moms: LG                 </div>               </td>               <td class="rc-event_time_name">                 <div class="rc-table_sleeve">                                    </div>               </td>             </tr>       </tbody>     </table>   </div>   <div class="rc-footer">     <p>       Powered by&nbsp;       <a href="https://www.planningcenter.com/calendar">Planning Center Calendar</a>     </p>   </div> </div> </div>
<script type="text/javascript">
  (function(){
    var rs = document.createElement("script");
    rs.type = "text/javascript";
    rs.async = true;
    rs.src = "//calendar.planningcenteronline.com/widgets/eJxj4ajmsGLLz2RulWe04kotzi8oqea0Yq9I81TiMjQ0NjZns2JzDbFiK_ZU4k_MyYlPLUvNKylms-YAipVmMs-SASouK_FU4gNJlmTmpkLkuAsSixJzi6sZAJFMGcs=64decca82d0dec7dd0874acece089d680844e71d.js";
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(rs,s);
  })();
</script></div><p><br></p></div></div>`,
  //prettier-ignore
  entry: { ...entry, selector: "[data-id=\"19657540\"]" },
  output: {
    data: [
      {
        type: "EmbedCode"
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p>'
              }
            }
          ]
        }
      }
    ]
  }
};
//#endregion

//#region Example 32 (Anthem Link color)
const ex32: Data = {
  html: `<div class="text-content text-1 editable" data-id="13073170" data-category="text"><div><p style="font-size: calc(var(--regular-text) * 1.2); letter-spacing: normal; line-height: calc(var(--regular-text) * 2);"><span style="font-size: inherit; font-style: italic;"><a href="https://biblia.com/bible/csb/Exod%2024.4" data-reference="Exod 24.4" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener">Exodus 24:4</a>;<span class="clovercustom">&nbsp;</span><a href="https://biblia.com/bible/csb/Deut%204.1-2" data-reference="Deut 4.1-2" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener">Deuteronomy 4:1-2</a>;<span class="clovercustom">&nbsp;</span><a href="https://biblia.com/bible/csb/Deuteronomy%2017.19" data-reference="Deuteronomy 17.19" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener">17:19</a>;<span class="clovercustom">&nbsp;</span><a href="https://biblia.com/bible/csb/Josh%208.34" data-reference="Josh 8.34" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener">Joshua 8:34</a>;<span class="clovercustom">&nbsp;</span><a href="https://biblia.com/bible/csb/Ps%2019.7-10" data-reference="Ps 19.7-10" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener">Psalms 19:7-10</a>;<span class="clovercustom">&nbsp;</span><a href="https://biblia.com/bible/csb/Psalms%20119.11" data-reference="Psalms 119.11" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener">119:11</a>,<a href="https://biblia.com/bible/csb/Psalms%20119.89" data-reference="Psalms 119.89" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener">89</a>,<a href="https://biblia.com/bible/csb/Psalms%20119.105" data-reference="Psalms 119.105" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener">105</a>,<a href="https://biblia.com/bible/csb/Psalms%20119.140" data-reference="Psalms 119.140" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener">140</a>;<span class="clovercustom">&nbsp;</span><a href="https://biblia.com/bible/csb/Isa%2034.16" data-reference="Isa 34.16" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener">Isaiah 34:16</a>;<span class="clovercustom">&nbsp;</span><a href="https://biblia.com/bible/csb/Isaiah%2040.8" data-reference="Isaiah 40.8" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener">40:8</a>;<span class="clovercustom">&nbsp;</span><a href="https://biblia.com/bible/csb/Jer%2015.16" data-reference="Jer 15.16" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener">Jeremiah 15:16</a>;<span class="clovercustom">&nbsp;</span><a href="https://biblia.com/bible/csb/Jeremiah%2036.1-32" data-reference="Jeremiah 36.1-32" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener">36:1-32</a>;<span class="clovercustom">&nbsp;</span><a href="https://biblia.com/bible/csb/Matt%205.17-18" data-reference="Matt 5.17-18" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener">Matthew 5:17-18</a>;<span class="clovercustom">&nbsp;</span><a href="https://biblia.com/bible/csb/Matthew%2022.29" data-reference="Matthew 22.29" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener">22:29</a>;<span class="clovercustom">&nbsp;</span><a href="https://biblia.com/bible/csb/Luke%2021.33" data-reference="Luke 21.33" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener">Luke 21:33</a>;<span class="clovercustom">&nbsp;</span><a href="https://biblia.com/bible/csb/Luke%2024.44-46" data-reference="Luke 24.44-46" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener">24:44-46</a>;<span class="clovercustom">&nbsp;</span><a href="https://biblia.com/bible/csb/John%205.39" data-reference="John 5.39" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener">John 5:39</a>;<span class="clovercustom">&nbsp;</span><a href="https://biblia.com/bible/csb/John%2016.13-15" data-reference="John 16.13-15" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener">16:13-15</a>;<span class="clovercustom">&nbsp;</span><a href="https://biblia.com/bible/csb/John%2017.17" data-reference="John 17.17" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener">17:17</a>;<span class="clovercustom">&nbsp;</span><a href="https://biblia.com/bible/csb/Acts%202.16ff" data-reference="Acts 2.16ff" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener">Acts 2:16ff</a>.;<span class="clovercustom">&nbsp;</span><a href="https://biblia.com/bible/csb/Acts%2017.11" data-reference="Acts 17.11" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener">17:11</a>;<span class="clovercustom">&nbsp;</span><a href="https://biblia.com/bible/csb/Rom%2015.4" data-reference="Rom 15.4" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener">Romans 15:4</a>;<span class="clovercustom">&nbsp;</span><a href="https://biblia.com/bible/csb/Romans%2016.25-26" data-reference="Romans 16.25-26" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener">16:25-26</a>;<span class="clovercustom">&nbsp;</span><a href="https://biblia.com/bible/csb/2%20Tim%203.15-17" data-reference="2 Tim 3.15-17" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener">2 Timothy 3:15-17</a>;<span class="clovercustom">&nbsp;</span><a href="https://biblia.com/bible/csb/Heb%201.1-2" data-reference="Heb 1.1-2" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener">Hebrews 1:1-2</a>;<span class="clovercustom">&nbsp;</span><a href="https://biblia.com/bible/csb/Hebrews%204.12" data-reference="Hebrews 4.12" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener">4:12</a>;<span class="clovercustom">&nbsp;</span><a href="https://biblia.com/bible/csb/1%20Pet%201.25" data-reference="1 Pet 1.25" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener">1 Peter 1:25</a>;<span class="clovercustom">&nbsp;</span><a href="https://biblia.com/bible/csb/2%20Pet%201.19-21" data-reference="2 Pet 1.19-21" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener">2 Peter 1:19-21</a>.</span></p></div></div>`,
  //prettier-ignore
  entry: { ...entry, selector: "[data-id=\"13073170\"]" },
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-0"><em style=""><a data-reference="Exod 24.4" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fbiblia.com%2Fbible%2Fcsb%2FExod%252024.4%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span>Exodus 24:4</span></a>;<span>&nbsp;</span><a data-reference="Deut 4.1-2" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fbiblia.com%2Fbible%2Fcsb%2FDeut%25204.1-2%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D">Deuteronomy 4:1-2</a>;<span>&nbsp;</span><a data-reference="Deuteronomy 17.19" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fbiblia.com%2Fbible%2Fcsb%2FDeuteronomy%252017.19%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D">17:19</a>;<span>&nbsp;</span><a data-reference="Josh 8.34" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fbiblia.com%2Fbible%2Fcsb%2FJosh%25208.34%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D">Joshua 8:34</a>;<span>&nbsp;</span><a data-reference="Ps 19.7-10" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fbiblia.com%2Fbible%2Fcsb%2FPs%252019.7-10%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D">Psalms 19:7-10</a>;<span>&nbsp;</span><a data-reference="Psalms 119.11" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fbiblia.com%2Fbible%2Fcsb%2FPsalms%2520119.11%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D">119:11</a>,<a data-reference="Psalms 119.89" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fbiblia.com%2Fbible%2Fcsb%2FPsalms%2520119.89%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D">89</a>,<a data-reference="Psalms 119.105" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fbiblia.com%2Fbible%2Fcsb%2FPsalms%2520119.105%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D">105</a>,<a data-reference="Psalms 119.140" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fbiblia.com%2Fbible%2Fcsb%2FPsalms%2520119.140%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D">140</a>;<span>&nbsp;</span><a data-reference="Isa 34.16" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fbiblia.com%2Fbible%2Fcsb%2FIsa%252034.16%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D">Isaiah 34:16</a>;<span>&nbsp;</span><a data-reference="Isaiah 40.8" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fbiblia.com%2Fbible%2Fcsb%2FIsaiah%252040.8%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D">40:8</a>;<span>&nbsp;</span><a data-reference="Jer 15.16" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fbiblia.com%2Fbible%2Fcsb%2FJer%252015.16%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D">Jeremiah 15:16</a>;<span>&nbsp;</span><a data-reference="Jeremiah 36.1-32" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fbiblia.com%2Fbible%2Fcsb%2FJeremiah%252036.1-32%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D">36:1-32</a>;<span>&nbsp;</span><a data-reference="Matt 5.17-18" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fbiblia.com%2Fbible%2Fcsb%2FMatt%25205.17-18%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D">Matthew 5:17-18</a>;<span>&nbsp;</span><a data-reference="Matthew 22.29" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fbiblia.com%2Fbible%2Fcsb%2FMatthew%252022.29%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D">22:29</a>;<span>&nbsp;</span><a data-reference="Luke 21.33" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fbiblia.com%2Fbible%2Fcsb%2FLuke%252021.33%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D">Luke 21:33</a>;<span>&nbsp;</span><a data-reference="Luke 24.44-46" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fbiblia.com%2Fbible%2Fcsb%2FLuke%252024.44-46%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D">24:44-46</a>;<span>&nbsp;</span><a data-reference="John 5.39" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fbiblia.com%2Fbible%2Fcsb%2FJohn%25205.39%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D">John 5:39</a>;<span>&nbsp;</span><a data-reference="John 16.13-15" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fbiblia.com%2Fbible%2Fcsb%2FJohn%252016.13-15%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D">16:13-15</a>;<span>&nbsp;</span><a data-reference="John 17.17" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fbiblia.com%2Fbible%2Fcsb%2FJohn%252017.17%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D">17:17</a>;<span>&nbsp;</span><a data-reference="Acts 2.16ff" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fbiblia.com%2Fbible%2Fcsb%2FActs%25202.16ff%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D">Acts 2:16ff</a>.;<span>&nbsp;</span><a data-reference="Acts 17.11" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fbiblia.com%2Fbible%2Fcsb%2FActs%252017.11%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D">17:11</a>;<span>&nbsp;</span><a data-reference="Rom 15.4" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fbiblia.com%2Fbible%2Fcsb%2FRom%252015.4%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D">Romans 15:4</a>;<span>&nbsp;</span><a data-reference="Romans 16.25-26" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fbiblia.com%2Fbible%2Fcsb%2FRomans%252016.25-26%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D">16:25-26</a>;<span>&nbsp;</span><a data-reference="2 Tim 3.15-17" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fbiblia.com%2Fbible%2Fcsb%2F2%2520Tim%25203.15-17%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D">2 Timothy 3:15-17</a>;<span>&nbsp;</span><a data-reference="Heb 1.1-2" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fbiblia.com%2Fbible%2Fcsb%2FHeb%25201.1-2%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D">Hebrews 1:1-2</a>;<span>&nbsp;</span><a data-reference="Hebrews 4.12" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fbiblia.com%2Fbible%2Fcsb%2FHebrews%25204.12%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D">4:12</a>;<span>&nbsp;</span><a data-reference="1 Pet 1.25" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fbiblia.com%2Fbible%2Fcsb%2F1%2520Pet%25201.25%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D">1 Peter 1:25</a>;<span>&nbsp;</span><a data-reference="2 Pet 1.19-21" data-version="csb" data-purpose="bible-reference" target="_blank" rel="noopener" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fbiblia.com%2Fbible%2Fcsb%2F2%2520Pet%25201.19-21%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D">2 Peter 1:19-21</a>.</em></p>'
              }
            }
          ]
        }
      }
    ]
  }
};
//#endregion

//#region Example 33 (Anthem Icon aligment)
const ex33: Data = {
  html: `<div class="text-content text-0 editable" data-id="18444100" data-category="text"><div><p style="font-size: 1.1968em;"><a href="https://www.google.com/maps/place/101+N+Palm+Ave,+Frostproof,+FL+33843/@27.7475396,-81.537104,17z/data=!3m1!4b1!4m5!3m4!1s0x88dd00b77fe4c2e9:0xe2e0f357efc9cf64!8m2!3d27.7475396!4d-81.5349153" data-location="external" data-button="false" data-detail="https://www.google.com/maps/place/101+N+Palm+Ave,+Frostproof,+FL+33843/@27.7475396,-81.537104,17z/data=!3m1!4b1!4m5!3m4!1s0x88dd00b77fe4c2e9:0xe2e0f357efc9cf64!8m2!3d27.7475396!4d-81.5349153" data-category="link" target="_blank" class="cloverlinks" style="color: rgb(227, 227, 227);">101 N Palm Ave.</a></p><p style="text-align: right;"><a class="socialIconLink cloverlinks" href="https://www.facebook.com/Firstpresfrostproof" data-location="external" data-button="false" data-detail="https://www.facebook.com/Firstpresfrostproof" data-category="link" target="_blank" style="letter-spacing: 0.1504px; background-color: rgb(80, 80, 80); outline-style: initial; outline-width: 0px; color: rgb(202, 232, 79); font-size: 26.9998px;"><span data-socialicon="facebook"><span class="socialIconSymbol" style="font-size: 1.8519em; color: rgb(227, 227, 227);" aria-hidden="true"></span><span class="sr-only">facebook</span></span> <span class="clovercustom" style="font-size: 1.8519em;"> &nbsp;</span></a><a class="socialIconLink cloverlinks" href="https://www.google.com/maps/place/101+N+Palm+Ave,+Frostproof,+FL+33843/@27.7475396,-81.537104,17z/data=!3m1!4b1!4m5!3m4!1s0x88dd00b77fe4c2e9:0xe2e0f357efc9cf64!8m2!3d27.7475396!4d-81.5349153" data-location="external" data-button="false" data-detail="https://www.google.com/maps/place/101+N+Palm+Ave,+Frostproof,+FL+33843/@27.7475396,-81.537104,17z/data=!3m1!4b1!4m5!3m4!1s0x88dd00b77fe4c2e9:0xe2e0f357efc9cf64!8m2!3d27.7475396!4d-81.5349153" data-category="link" target="_blank" style="letter-spacing: 0.1504px; background-color: rgb(80, 80, 80); color: rgb(227, 227, 227); font-size: 26.9998px;"><span data-socialicon="map"><span class="socialIconSymbol" style="font-size: 1.8519em;" aria-hidden="true"></span><span class="sr-only">map</span></span><span class="clovercustom" style="font-size: 1.8519em;"> </span></a> </p></div></div>`,
  //prettier-ignore
  entry: { ...entry, selector: "[data-id=\"18444100\"]" },
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
                text: '<p class="brz-fs-lg-1_2 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><a data-location="external" data-button="false" data-detail="https://www.google.com/maps/place/101+N+Palm+Ave,+Frostproof,+FL+33843/@27.7475396,-81.537104,17z/data=!3m1!4b1!4m5!3m4!1s0x88dd00b77fe4c2e9:0xe2e0f357efc9cf64!8m2!3d27.7475396!4d-81.5349153" data-category="link" target="_blank" style="color: rgb(227, 227, 227); " data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fwww.google.com%2Fmaps%2Fplace%2F101%2BN%2BPalm%2BAve%2C%2BFrostproof%2C%2BFL%2B33843%2F%4027.7475396%2C-81.537104%2C17z%2Fdata%3D!3m1!4b1!4m5!3m4!1s0x88dd00b77fe4c2e9%3A0xe2e0f357efc9cf64!8m2!3d27.7475396!4d-81.5349153%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span style="color: rgb(227, 227, 227); ">101 N Palm Ave.</span></a></p>'
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
          horizontalAlign: undefined,
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                bgColorHex: "#505050",
                bgColorOpacity: undefined,
                bgColorPalette: "",
                colorHex: "#e3e3e3",
                colorOpacity: 1,
                colorPalette: "",
                customSize: 26,
                hoverColorHex: "#e3e3e3",
                hoverColorOpacity: 0.8,
                hoverColorPalette: "",
                linkExternal: "https://www.facebook.com/Firstpresfrostproof",
                linkExternalBlank: "on",
                linkType: "external",
                name: "facebook-f",
                padding: 7,
                type: "fa"
              }
            },
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                bgColorHex: "#505050",
                bgColorOpacity: undefined,
                bgColorPalette: "",
                customSize: 26,
                linkExternal:
                  "https://www.google.com/maps/place/101+N+Palm+Ave,+Frostproof,+FL+33843/@27.7475396,-81.537104,17z/data=!3m1!4b1!4m5!3m4!1s0x88dd00b77fe4c2e9:0xe2e0f357efc9cf64!8m2!3d27.7475396!4d-81.5349153",
                linkExternalBlank: "on",
                linkType: "external",
                name: "map-marker-alt",
                padding: 7,
                type: "fa"
              }
            }
          ]
        }
      }
    ]
  }
};
//#endregion

//#region Example 34 (Anthem Text with font tag)
const ex34: Data = {
  html: `<div class="text-content text-1 editable" data-id="13030114" data-category="text"><div><p style="font-family: &quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, &quot;Lucida Sans&quot;, Geneva, Verdana, sans-serif; font-weight: 700; text-align: left; font-size: 0.9973em; color: rgb(0, 83, 166); line-height: 1.4em;">Part 3 - We respond to God's Word</p><ol style="color: rgb(0, 83, 166); font-family: &quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, &quot;Lucida Sans&quot;, Geneva, Verdana, sans-serif; font-style: normal; font-weight: 400;"><li><font color="#0053a6" face="Lucida Grande, Lucida Sans Unicode, Lucida Sans, Geneva, Verdana, sans-serif" style="font-weight: 400;">In songs of praise</font></li><li><font color="#0053a6" face="Lucida Grande, Lucida Sans Unicode, Lucida Sans, Geneva, Verdana, sans-serif" style="font-weight: 400;">In offerings of thanks to God (We support the sharing of his Word)</font></li><li>In prayers for our congregation, our world, and the Church</li></ol></div></div>`,
  //prettier-ignore
  entry: { ...entry, selector: "[data-id=\"13030114\"]" },
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(0, 83, 166); font-weight: 700; ">Part 3 - We respond to God\'s Word</span></p><ol><li style="color: rgb(0, 83, 166); " class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 400; color: rgb(0, 83, 166); ">In songs of praise</span></li><li style="color: rgb(0, 83, 166); " class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 400; color: rgb(0, 83, 166); ">In offerings of thanks to God (We support the sharing of his Word)</span></li><li class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>In prayers for our congregation, our world, and the Church</span></li></ol>'
              }
            }
          ]
        }
      }
    ]
  }
};
//#endregion

//#region Example 35 (Voyage Text with bold link)
const ex35: Data = {
  html: `<div class="text-content text-0 editable" data-id="23579203" data-category="text"><div><p><a class="socialIconLink cloverlinks sites-button" href="https://facebook.com/CanaanBaptistChurch.Birmingham" data-location="external" data-button="true" data-detail="https://facebook.com/CanaanBaptistChurch.Birmingham" data-category="link" target="_blank" role="button"><span data-socialicon="facebook"><span class="socialIconSymbol" aria-hidden="true"></span><span class="sr-only">facebook</span></span></a> <a class="socialIconLink cloverlinks sites-button" href="https://www.youtube.com/@CanaanBaptistChurch-Birmingham" data-location="external" data-button="true" data-detail="https://www.youtube.com/@CanaanBaptistChurch-Birmingham" data-category="link" target="_blank" role="button"><span data-socialicon="youtube"><span class="socialIconSymbol" aria-hidden="true"></span><span class="sr-only">youtube</span></span></a><br></p><p>Canaan Baptist Church</p><p>2543 Morgan Rd Bessemer, AL 35022</p><p>205-425-4381</p></div></div>`,
  //prettier-ignore
  entry: { ...entry, selector: "[data-id=\"23579203\"]" },
  output: {
    data: [
      {
        type: "Cloneable",
        value: {
          _id: "1",
          _styles: ["wrapper-clone", "wrapper-clone--button"],
          horizontalAlign: "center",
          items: [
            {
              type: "Button",
              value: {
                _id: "1",
                _styles: ["button"],
                borderStyle: "none",
                fontFamily: "lato",
                fontFamilyType: "upload",
                fontStyle: "",
                iconName: "facebook-f",
                iconType: "fa",
                lineHeight: 1.3,
                linkExternal:
                  "https://facebook.com/CanaanBaptistChurch.Birmingham",
                linkExternalBlank: "on",
                linkType: "external",
                mobileFontStyle: "",
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontStyle: "",
                tabletLineHeight: 1.2,
                text: ""
              }
            },
            {
              type: "Button",
              value: {
                _id: "1",
                _styles: ["button"],
                borderStyle: "none",
                fontFamily: "lato",
                fontFamilyType: "upload",
                fontStyle: "",
                iconName: "youtube",
                iconType: "fa",
                lineHeight: 1.3,
                linkExternal:
                  "https://www.youtube.com/@CanaanBaptistChurch-Birmingham",
                linkExternalBlank: "on",
                linkType: "external",
                mobileFontStyle: "",
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontStyle: "",
                tabletLineHeight: 1.2,
                text: ""
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Canaan Baptist Church</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>2543 Morgan Rd Bessemer, AL 35022</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>205-425-4381</span></p>'
              }
            }
          ]
        }
      }
    ]
  }
};
//#endregion

//#region Example 36 (Voyage em tag with font-style: normal)
const ex36: Data = {
  html: `<div class="text-content text-1 editable" data-id="24764599" data-category="text"><div><p style="line-height: 12.7pt; font-weight: 700; font-size: 1.4423em; color: rgb(255, 255, 255);">PETERS CREEK VBS</p><p style="text-align: left; color: rgb(255, 255, 255); font-weight: 700; font-size: 1.0216em;"><br></p><p style="text-align: left; color: rgb(255, 255, 255); font-weight: 200; font-size: 1.0216em;"><br></p><p style="text-align: left; color: rgb(255, 255, 255); font-weight: 200; font-size: 1.0216em;">See Highlights of 2024 Camp Firelight<br></p><p style="text-align: left; color: rgb(255, 255, 255); font-weight: 600; font-size: 1.0216em;"><a style="color: rgb(61, 161, 255);" href="https://youtu.be/J081cDyNfTY" data-location="external" data-button="false" data-detail="https://youtu.be/J081cDyNfTY" data-category="link" target="_blank" class="cloverlinks"><u>Click Here</u></a></p><p style="line-height: 1.2em; text-align: left; color: rgb(255, 255, 255); font-style: italic; font-weight: 200; font-size: 1.0216em;"><br></p><p style="line-height: 12.7pt; text-align: left; color: rgb(255, 255, 255); font-style: italic; font-weight: 200; font-size: 1.0216em;"><span class="clovercustom" style="letter-spacing: normal;">********</span></p><p style="line-height: 12.7pt; text-align: left; color: rgb(255, 255, 255); font-style: italic; font-weight: 200; font-size: 1.0216em;"><br></p><p style="text-align: left; color: rgb(255, 255, 255); font-weight: 200; font-size: 1.0216em;">Peters Creek Vacation Bible School welcomes children who are entering Kindergarten through 5<sup>th</sup> grade.&nbsp;</p><p style="text-align: left; color: rgb(255, 255, 255); font-weight: 200; font-size: 1.0216em;"><br></p><p style="text-align: left; font-weight: 200; color: rgb(255, 255, 255); font-size: 1.0216em;">Anyone entering 6th grade or older is asked to consider registering to be a<span class="clovercustom" style="letter-spacing: 0em;"> volunteer.</span></p><p style="text-align: left; font-weight: 200; color: rgb(255, 255, 255); font-size: 1.0216em;"><br></p><p style="text-align: left; color: rgb(255, 255, 255); font-weight: 200; font-style: normal; font-size: 1.0216em;">To look back over the successful week of the<br></p><p style="text-align: left; color: rgb(255, 255, 255); font-weight: 200; font-style: normal; font-size: 1.0216em;"><em style="letter-spacing: normal;"><span class="clovercustom" style="font-style: normal;">Quest for the King’s </span></em><em style=""><span class="clovercustom" style="font-style: normal;">Armor in 2022</span>, <a href="https://www.facebook.com/111659865538568/videos/582008206934445/" data-location="external" data-button="false" data-detail="https://www.facebook.com/111659865538568/videos/582008206934445/" data-category="link" target="_blank" class="cloverlinks" style="font-weight: 600; color: rgb(61, 161, 255); font-style: normal;"><u>Click Here</u></a> </em></p><p style="text-align: left; color: rgb(255, 255, 255); font-weight: 200; font-style: normal; font-size: 1.0216em;"><em style=""><br></em></p><p style="line-height: 1.7em; color: rgb(255, 255, 255); font-weight: 200; text-align: left; letter-spacing: 0em; font-style: normal; font-size: 1.0216em;">For a joyful look back at our Island Adventure 2021, check out this slide show! <a href="https://www.facebook.com/watch/?v=1657419827801259" data-location="external" data-button="false" data-detail="https://www.facebook.com/watch/?v=1657419827801259" data-category="link" target="_blank" class="cloverlinks" style="font-weight: 600; color: rgb(61, 161, 255);"><u>Click here</u></a></p></div></div>`,
  entry: { ...entry, selector: '[data-id="24764599"]' },
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
                text: '<p class="brz-fs-lg-1_44 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(255, 255, 255); font-weight: 700; ">PETERS CREEK VBS</span></p><p class="brz-fs-lg-1_02 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_02 brz-ff-lato brz-ft-upload brz-fw-lg-200 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_02 brz-ff-lato brz-ft-upload brz-fw-lg-200 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(255, 255, 255); font-weight: 200; ">See Highlights of 2024 Camp Firelight</span><br></p><p class="brz-fs-lg-1_02 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><a style="color: rgb(61, 161, 255); " data-location="external" data-button="false" data-detail="https://youtu.be/J081cDyNfTY" data-category="link" target="_blank" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fyoutu.be%2FJ081cDyNfTY%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><u><span>Click Here</span></u></a></p><p class="brz-fs-lg-1_02 brz-ff-lato brz-ft-upload brz-fw-lg-200 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_02 brz-ff-lato brz-ft-upload brz-fw-lg-200 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-0"><span style="color: rgb(255, 255, 255); font-weight: 200; ">********</span></p><p class="brz-fs-lg-1_02 brz-ff-lato brz-ft-upload brz-fw-lg-200 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_02 brz-ff-lato brz-ft-upload brz-fw-lg-200 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(255, 255, 255); font-weight: 200; ">Peters Creek Vacation Bible School welcomes children who are entering Kindergarten through 5</span><sup><span>th</span></sup><span style="color: rgb(255, 255, 255); font-weight: 200; "> grade.&nbsp;</span></p><p class="brz-fs-lg-1_02 brz-ff-lato brz-ft-upload brz-fw-lg-200 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_02 brz-ff-lato brz-ft-upload brz-fw-lg-200 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(255, 255, 255); font-weight: 200; ">Anyone entering 6th grade or older is asked to consider registering to be a</span><span style="color: rgb(255, 255, 255); font-weight: 200; "> volunteer.</span></p><p class="brz-fs-lg-1_02 brz-ff-lato brz-ft-upload brz-fw-lg-200 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_02 brz-ff-lato brz-ft-upload brz-fw-lg-200 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(255, 255, 255); font-weight: 200; ">To look back over the successful week of the</span><br></p><p class="brz-fs-lg-1_02 brz-ff-lato brz-ft-upload brz-fw-lg-200 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style=""><span style="">Quest for the King’s </span></span><span style=""><span style="">Armor in 2022</span>, <a data-location="external" data-button="false" data-detail="https://www.facebook.com/111659865538568/videos/582008206934445/" data-category="link" target="_blank" style="font-weight: 600; color: rgb(61, 161, 255); " data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fwww.facebook.com%2F111659865538568%2Fvideos%2F582008206934445%2F%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><u>Click Here</u></a> </span></p><p class="brz-fs-lg-1_02 brz-ff-lato brz-ft-upload brz-fw-lg-200 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><em style=""><br></em></p><p class="brz-fs-lg-1_02 brz-ff-lato brz-ft-upload brz-fw-lg-200 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(255, 255, 255); font-weight: 200; ">For a joyful look back at our Island Adventure 2021, check out this slide show! </span><a data-location="external" data-button="false" data-detail="https://www.facebook.com/watch/?v=1657419827801259" data-category="link" target="_blank" style="font-weight: 600; color: rgb(61, 161, 255); " data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fwww.facebook.com%2Fwatch%2F%3Fv%3D1657419827801259%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><u><span>Click here</span></u></a></p>'
              }
            }
          ]
        }
      }
    ]
  }
};

//#region Example 37 (Ember with spans unwrapped in p at first level)
const ex37: Data = {
  html: `<div class="text-content text-2 editable" data-id="25872932" data-category="text"><div><div style="line-height: 1.4em; text-align: left;"><span class="clovercustom" style="font-size: 1em; letter-spacing: -0.175192px; font-weight: 600; text-align: right;">Pre-School:</span><span class="clovercustom" style="font-size: 1em; letter-spacing: -0.175192px; text-align: right;">&nbsp;</span><span class="clovercustom" style="text-align: right;">Our goal is to provide developmentally appropriate educational classes for each child in a safe, loving, Christian environment. Ages 6 weeks through 4 year olds.</span><p><span class="clovercustom" style="letter-spacing: -0.175192px; font-size: 1em; font-weight: 600; text-align: center;"><br></span></p><p><span class="clovercustom" style="letter-spacing: -0.175192px; font-size: 1em; font-weight: 600; text-align: center;">Men's and Women's Ministries </span>provide Bible Study, fellowship and service opportunities.<br></p><p><span class="clovercustom" style="font-size: 1em; letter-spacing: 0em; font-weight: 600;"><br></span></p><p><span class="clovercustom" style="font-size: 1em; letter-spacing: 0em; font-weight: 600;">Visitor Visitation ministry<span class="clovercustom">&nbsp;</span></span>is focused on providing a free gift&nbsp; &nbsp;bag to 1st and 2nd time visitors with a follow-up via phone or email.<br></p><p><br></p><p><span class="clovercustom" style="font-size: 0.9557em; font-weight: 600; letter-spacing: -0.01em;">Hearts Still Growing:</span><span class="clovercustom" style="font-size: 1em; letter-spacing: 0em;">&nbsp;</span>a Bible class, fellowship and service opportunities for our 'seniors'.<br></p><p><br></p><p style="font-size: 0.9557em; line-height: 1.4em;"><span class="clovercustom" style="font-size: 0.9557em; font-weight: 600; letter-spacing: -0.01em;">Children's Homes:</span><span class="clovercustom">&nbsp;</span>serve as a safe loving environment for foster children.<br></p><p style="font-size: 0.9557em; line-height: 1.4em;"><br></p><p style="font-size: 0.9557em; line-height: 1.4em;"><span class="clovercustom" style="font-weight: 600;">Our Food Pantry<span class="clovercustom">&nbsp;</span></span>provides free food for individuals in our community.</p><p style="font-size: 0.9557em; line-height: 1.4em;"><br></p><p style="font-size: 0.9557em; line-height: 1.4em;"><span class="clovercustom" style="font-size: 0.9557em; letter-spacing: 0em; font-weight: 600;">Small Groups:</span><span class="clovercustom" style="font-size: 0.9557em; letter-spacing: 0em;">&nbsp;</span>10-12 people gather for prayer, Bible study, spiritual support and fellowship.<br></p><p style="font-size: 0.9557em; line-height: 1.4em;"><br></p><p style="font-size: 0.9557em; line-height: 1.4em;"><br></p><p style="font-size: 0.9557em; line-height: 1.4em;"><br style="font-size: 16.0007px; letter-spacing: -0.167424px;"></p></div></div></div>`,
  entry: { ...entry, selector: '[data-id="25872932"]' },
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-right brz-ls-lg-m_0_1"><span style="font-weight: 600; ">Pre-School:</span><span style="">&nbsp;</span><span style="">Our goal is to provide developmentally appropriate educational classes for each child in a safe, loving, Christian environment. Ages 6 weeks through 4 year olds.</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 600; "><br></span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-m_0_1"><span style="font-weight: 600; ">Men\'s and Women\'s Ministries </span><span>provide Bible Study, fellowship and service opportunities.</span><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 600; "><br></span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 600; ">Visitor Visitation ministry<span>&nbsp;</span></span><span>is focused on providing a free gift&nbsp; &nbsp;bag to 1st and 2nd time visitors with a follow-up via phone or email.</span><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-0_96 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_0"><span style="font-weight: 600; ">Hearts Still Growing:</span><span style="">&nbsp;</span><span>a Bible class, fellowship and service opportunities for our \'seniors\'.</span><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-0_96 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_0"><span style="font-weight: 600; ">Children\'s Homes:</span><span>&nbsp;</span><span>serve as a safe loving environment for foster children.</span><br></p><p class="brz-fs-lg-0_96 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-0_96 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 600; ">Our Food Pantry<span>&nbsp;</span></span><span>provides free food for individuals in our community.</span></p><p class="brz-fs-lg-0_96 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-0_96 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 600; ">Small Groups:</span><span style="">&nbsp;</span><span>10-12 people gather for prayer, Bible study, spiritual support and fellowship.</span><br></p><p class="brz-fs-lg-0_96 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-0_96 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-0_96 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br style=""></p>'
              }
            }
          ]
        }
      }
    ]
  }
};
//#endregion

//#region Example 38 (Anthem with paragraphs inserted in li tags)
const ex38: Data = {
  html: `<div class="text-content text-1 editable" data-id="9048275" data-category="text"><div><p dir="ltr" style="line-height: 1.2; font-family: &quot;Trebuchet MS&quot;, &quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, &quot;Lucida Sans&quot;, Tahoma, sans-serif; font-weight: 400; font-size: 1.3298em;">Unity Spiritual Center in the Rockies is a progressive spiritual community that emphasizes personal growth, living life with intention and joy, and making a positive difference in the world.&nbsp;<br></p><p dir="ltr" style="font-size: 1.3298em; line-height: 1.2; font-family: &quot;Trebuchet MS&quot;, &quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, &quot;Lucida Sans&quot;, Tahoma, sans-serif; font-weight: 400;"><br></p><p dir="ltr" style="line-height: 1.68; font-family: &quot;Trebuchet MS&quot;, &quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, &quot;Lucida Sans&quot;, Tahoma, sans-serif; font-weight: 400;"><span class="clovercustom" style="font-size: 15.5pt;">Ours is a vibrant and diverse community that welcomes and affirms people of all ages, races, genders, sexual orientations, and religious backgrounds.</span></p><p dir="ltr" style="line-height: 1.2; font-family: &quot;Trebuchet MS&quot;, &quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, &quot;Lucida Sans&quot;, Tahoma, sans-serif; font-size: 1.3298em; font-weight: 400;"><br></p><p dir="ltr" style="line-height: 1.68; font-family: &quot;Trebuchet MS&quot;, &quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, &quot;Lucida Sans&quot;, Tahoma, sans-serif; font-weight: 400;"><span class="clovercustom" style="font-size: 15.5pt;">Unity Spiritual Center may be the perfect place for you if you are looking for:</span></p><ul style="font-family: &quot;Trebuchet MS&quot;, &quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, &quot;Lucida Sans&quot;, Tahoma, sans-serif; font-weight: 400;"><li dir="ltr" aria-level="1" style="font-weight: 400; font-size: 1.3298em;"><p dir="ltr" role="presentation" style="line-height: 1.68; font-weight: 400;">An upbeat, practical approach to spirituality that incorporates wisdom from many faith traditions.</p></li><li dir="ltr" aria-level="1" style="font-weight: 400; font-size: 1.3298em;"><p dir="ltr" role="presentation" style="line-height: 1.68; font-weight: 400;">Tools that help you create a meaningful life.</p></li><li dir="ltr" aria-level="1" style="font-weight: 400; font-size: 1.3298em;"><p dir="ltr" role="presentation" style="line-height: 1.68; font-weight: 400;">A heart-based community with an "attitude of gratitude."</p></li></ul><p dir="ltr" style="line-height: 1.2; font-family: &quot;Trebuchet MS&quot;, &quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, &quot;Lucida Sans&quot;, Tahoma, sans-serif; font-size: 1.3298em; font-weight: 400;"><br><br><br><br><br></p><div style="font-family: &quot;Trebuchet MS&quot;, &quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, &quot;Lucida Sans&quot;, Tahoma, sans-serif; font-weight: 400; font-size: 1.3298em;"><br></div></div></div>`,
  entry: { ...entry, selector: '[data-id="9048275"]' },
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
                text: '<p dir="ltr" class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 400; ">Unity Spiritual Center in the Rockies is a progressive spiritual community that emphasizes personal growth, living life with intention and joy, and making a positive difference in the world.&nbsp;</span><br></p><p dir="ltr" class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p dir="ltr" class="brz-fs-lg-15_5 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 400; ">Ours is a vibrant and diverse community that welcomes and affirms people of all ages, races, genders, sexual orientations, and religious backgrounds.</span></p><p dir="ltr" class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p dir="ltr" class="brz-fs-lg-15_5 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 400; ">Unity Spiritual Center may be the perfect place for you if you are looking for:</span></p><ul><li dir="ltr" aria-level="1" style="font-weight: 400; " class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 400; ">An upbeat, practical approach to spirituality that incorporates wisdom from many faith traditions.</span></li><li dir="ltr" aria-level="1" style="font-weight: 400; " class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 400; ">Tools that help you create a meaningful life.</span></li><li dir="ltr" aria-level="1" style="font-weight: 400; " class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 400; ">A heart-based community with an "attitude of gratitude."</span></li></ul><p dir="ltr" class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br><br><br><br><br></p><p class="brz-fs-lg-1_33 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p>'
              }
            }
          ]
        }
      }
    ]
  }
};

//#endregion

//#region Example 39 (Anthem text of icons are missing)
const ex39: Data = {
  html: `<div class="text-content text-1 editable" data-id="17909016" data-category="text"><div><p style="text-align: center; line-height: 2.3em; color: rgb(235, 235, 235);">Watch and listen to the latest sermons from Hillside.</p><p style="font-size: 18.56px; letter-spacing: 1.2992px; text-align: center; line-height: 2.3em;"><a href="https://podcasts.apple.com/us/podcast/hillside-evangelical-free-church/id1547915934" data-location="external" data-detail="https://podcasts.apple.com/us/podcast/hillside-evangelical-free-church/id1547915934" data-category="link" target="_blank" class="cloverlinks socialIconLink" style="font-size: 18.56px; letter-spacing: 1.2992px;"><span data-socialicon="roundedpodcast"><span class="socialIconSymbol" style="color: rgb(255, 255, 255);" aria-hidden="true"></span><span class="sr-only">roundedpodcast</span></span><span class="clovercustom" style="color: rgb(255, 255, 255);">&nbsp;</span>Apple Podcasts</a> <a class="socialIconLink cloverlinks" style="color: rgb(255, 255, 255);" href="https://podcasters.spotify.com/pod/show/hillsideefc" data-location="external" data-button="false" data-detail="https://podcasters.spotify.com/pod/show/hillsideefc" data-category="link" target="_blank"><span data-socialicon="roundedspotify"><span class="socialIconSymbol" aria-hidden="true"></span><span class="sr-only">roundedspotify</span></span></a><a href="https://podcasters.spotify.com/pod/show/hillsideefc" data-location="external" data-detail="https://podcasters.spotify.com/pod/show/hillsideefc" data-category="link" target="_blank" class="cloverlinks socialIconLink" style="font-size: 18.56px; letter-spacing: 1.2992px;" data-button="false"> &nbsp;Spotify</a></p></div></div>`,
  entry: { ...entry, selector: '[data-id="17909016"]' },
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span style="color: rgb(235, 235, 235); ">Watch and listen to the latest sermons from Hillside.</span></p>'
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
          horizontalAlign: undefined,
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                colorHex: "#ffffff",
                colorOpacity: 0.99,
                colorPalette: "",
                customSize: 26,
                hoverColorHex: "#ffffff",
                hoverColorOpacity: 0.8,
                hoverColorPalette: "",
                linkExternal:
                  "https://podcasts.apple.com/us/podcast/hillside-evangelical-free-church/id1547915934",
                linkExternalBlank: "on",
                linkType: "external",
                name: "podcast",
                padding: 7,
                type: "fa"
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
                text: '<p class="brz-fs-lg-18_56 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-1_2"><a data-location="external" data-detail="https://podcasts.apple.com/us/podcast/hillside-evangelical-free-church/id1547915934" data-category="link" target="_blank" style="" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fpodcasts.apple.com%2Fus%2Fpodcast%2Fhillside-evangelical-free-church%2Fid1547915934%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span>Apple Podcasts</span></a></p>'
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
          horizontalAlign: undefined,
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                customSize: 26,
                linkExternal:
                  "https://podcasters.spotify.com/pod/show/hillsideefc",
                linkExternalBlank: "on",
                linkType: "external",
                name: "spotify",
                padding: 7,
                type: "fa"
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
                text: '<p class="brz-fs-lg-18_56 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><a data-location="external" data-detail="https://podcasters.spotify.com/pod/show/hillsideefc" data-category="link" target="_blank" style="" data-button="false" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fpodcasters.spotify.com%2Fpod%2Fshow%2Fhillsideefc%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span> &nbsp;Spotify</span></a></p>'
              }
            }
          ]
        }
      }
    ]
  }
};

//#endregion

// #region Example 40 (Anthem multiple icons with text lose the text)
const ex40: Data = {
  html: `<div class="text-content text-1 editable" data-id="25322669" data-category="text"><div><p><span class="clovercustom" style="font-family: Biryani, sans-serif; font-weight: 300; font-size: 1.3125em;">The New Castle Public Library Book Mobile
will be at the church </span><span style="font-family: Biryani, sans-serif; font-size: 21px; letter-spacing: 0.16px;">from 4:00 - 5:00P.M. on select </span><span style="font-family: Biryani, sans-serif; font-size: 1.3125em; letter-spacing: 0.01em;">WEDNESDAYS before AWANA.&nbsp; Stop in early to check out the books and stay for AWANA.</span></p><p><span class="clovercustom" style="font-family: Biryani, sans-serif; font-weight: 300; font-size: 1.3125em;"><br></span></p><p><span class="clovercustom" style="font-family: Biryani, sans-serif; font-weight: 300; font-size: 1.3125em;">September 11 &nbsp;<span data-icon="book-open"><span class="clovericons fas" aria-hidden="true"></span><span class="sr-only">Book Open</span></span> &nbsp; October 16 &nbsp;<span data-icon="book-open"><span class="clovericons fas" aria-hidden="true"></span><span class="sr-only">Book Open</span></span> &nbsp; November 6 <span data-icon="book-open"><span class="clovericons fas" aria-hidden="true"></span><span class="sr-only">Book Open</span></span> &nbsp; December 11 <span data-icon="book-open"><span class="clovericons fas" aria-hidden="true"></span><span class="sr-only">Book Open</span></span> &nbsp; January 15&nbsp; <span data-icon="book-open"><span class="clovericons fas" aria-hidden="true"></span><span class="sr-only">Book Open</span></span>&nbsp; February 5&nbsp; <span data-icon="book-open"><span class="clovericons fas" aria-hidden="true"></span><span class="sr-only">Book Open</span></span>&nbsp; March 5</span></p><p><span class="clovercustom" style="font-family: Biryani, sans-serif; font-weight: 300; font-size: 1.3125em;"><br></span></p><p><span class="clovercustom" style="font-family: Biryani, sans-serif; font-weight: 300; font-size: 1.3125em;">Please stop by and bring the
kids or grandkids to check it out.</span><br></p></div></div>`,
  entry: { ...entry, selector: '[data-id="25322669"]' },
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
                text: `<p class="brz-fs-lg-1_31 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-0_0"><span style="font-weight: 300; ">The New Castle Public Library Book Mobile will be at the church </span><span style="">from 4:00 - 5:00P.M. on select </span><span style="">WEDNESDAYS before AWANA.&nbsp; Stop in early to check out the books and stay for AWANA.</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 300; "><br></span></p>`
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
                text: '<p class="brz-fs-lg-1_31 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 300; ">September 11</span></p>'
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
          horizontalAlign: undefined,
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                customSize: 26,
                name: "book-open",
                padding: 7,
                type: "fa"
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
                text: '<p class="brz-fs-lg-1_31 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 300; ">October 16</span></p>'
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
          horizontalAlign: undefined,
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                customSize: 26,
                name: "book-open",
                padding: 7,
                type: "fa"
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
                text: '<p class="brz-fs-lg-1_31 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 300; ">November 6</span></p>'
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
          horizontalAlign: undefined,
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                customSize: 26,
                name: "book-open",
                padding: 7,
                type: "fa"
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
                text: '<p class="brz-fs-lg-1_31 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 300; ">December 11</span></p>'
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
          horizontalAlign: undefined,
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                customSize: 26,
                name: "book-open",
                padding: 7,
                type: "fa"
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
                text: '<p class="brz-fs-lg-1_31 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 300; ">January 15</span></p>'
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
          horizontalAlign: undefined,
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                customSize: 26,
                name: "book-open",
                padding: 7,
                type: "fa"
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
                text: '<p class="brz-fs-lg-1_31 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 300; ">February 5</span></p>'
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
          horizontalAlign: undefined,
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                customSize: 26,
                name: "book-open",
                padding: 7,
                type: "fa"
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
                text: '<p class="brz-fs-lg-1_31 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 300; ">March 5</span></p>'
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 300; "><br></span></p><p class="brz-fs-lg-1_31 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 300; ">Please stop by and bring the kids or grandkids to check it out.</span><br></p>'
              }
            }
          ]
        }
      }
    ]
  }
};

//#endregion

//#region Example 41 (Anthem with table tags inserted in text)
const ex41: Data = {
  html: `<div class="text-content text-1 editable" data-category="text" data-id="15635349"><div><p><br></p><blockquote style="font-style: normal; font-weight: normal; letter-spacing: normal; text-align: start; text-decoration: none; font-size: 0.9309em;" type="cite"><table bgcolor="#FFFFFF" cellpadding="0" cellspacing="0" role="presentation" valign="top" width="100%"><tbody><tr valign="top"><td valign="top"><div style="line-height: 1.2;"><div style="line-height: 1.2; font-weight: 700;"><strong>Community Conversations - Tuesday’s at 12:00 - 1:15 pm</strong></div><div style="line-height: 1.2; font-weight: 400;">This group practices using tools for building multicultural community that recognizes, understands, appreciates and utilizes differences at personal, interpersonal, institutional and cultural levels. The group is always open.<br></div><div style="line-height: 1.2; font-weight: 400;"><br></div><div style="line-height: 1.2; font-weight: 400;">https://us02web.zoom.us/j/96076302991?pwd=eTlMNExGKzFKc2YwUDlTV0UvUkVQUT09</div><div style="line-height: 1.2;">
<p style="line-height: normal;">Meeting ID: 960 7630 2991<br></p>
<p style="line-height: normal;">Passcode: 254042</p>
<p style="line-height: normal;">One tap mobile</p>
<p style="line-height: normal;">+19292056099,,96076302991#,,,,*254042#</p><p style="line-height: 1.2;"><br></p><p style="line-height: 1.2;"><strong style="font-style: inherit; letter-spacing: 0.01em;">Seekers Sunday School Class </strong><span style="font-style: inherit; letter-spacing: 0.01em;">-</span><span class="clovercustom" style="font-style: inherit; letter-spacing: 0.01em;">&nbsp;</span><u style="font-style: inherit; letter-spacing: 0.01em;">Sundays</u><span style="font-style: inherit; letter-spacing: 0.01em;">, 9:30 am - 10:30 am via Zoom</span></p><p style="line-height: 1.2;"><span class="clovercustom" style="font-style: inherit;">https://us02web.zoom.us/j/806127553?</span>pwd=YUw1V213OVJnUmpxUjAvS2l4ZUtuQT09</p><p style="line-height: 1.2;"><span class="clovercustom" style="font-style: inherit;">Meeting ID: 806 127 553</span></p><p style="line-height: 1.2;"><span class="clovercustom" style="font-style: inherit;">Password: seekers</span></p><p style="line-height: 1.2;"><span class="clovercustom" style="font-style: inherit;">One tap mobile&nbsp; 19292056099,,806127553#,,,,*4838220#</span></p><p style="line-height: 1.2;"><span class="clovercustom" style="font-style: inherit;"><strong style=""><br></strong></span></p><p style="line-height: 1.2;"><span class="clovercustom" style="font-style: inherit;"><strong style="">Building the Beloved Community </strong>- Wednesdays 5:00 - 6:30 p.m. in person and by Zoom.&nbsp;</span><span style="font-size: 0.94em; letter-spacing: 0.01em;">We will share a light meal and build the beloved community that Jesus makes possible - community where both our common humanity and our beautiful differences are recognized, understood and appreciated.&nbsp;</span>We will be accepting that gift of God offered to us in our baptism . . . the freedom and power to resist evil, injustice and oppression in whatever forms they present themselves.&nbsp;<span style="font-size: 0.94em; letter-spacing: 0.01em;">Here is the Zoom link to join online:&nbsp;</span><a data-behavior="truncate" href="https://us02web.zoom.us/j/81757191393?pwd=r0sscYwL949W_RJUtdBYwQvgT5T81S.1" rel="noreferrer" style="font-size: 0.94em; letter-spacing: 0.01em; text-decoration: underline;" target="_blank">https://us02web.zoom.us/j/81757191393?pwd=r0sscYwL949W_RJUtdBYwQvgT5T81S.1</a></p><p>Meeting ID: 817 5719 1393<br></p><p>Passcode: 746729</p><p>One tap mobile</p><p>+19292056099,,81757191393#,,,,*746729#</p><p><br></p></div></div></td></tr></tbody></table></blockquote><p style="font-size: 14px; letter-spacing: normal; line-height: 1.2;"><span class="clovercustom" style="font-style: inherit;"><strong style="">Prayer Circle</strong><span class="clovercustom">&nbsp;</span>-<span class="clovercustom">&nbsp;</span><u style="">Tuesdays</u><span class="clovercustom">&nbsp;</span>at 10 am, Wesley Room</span></p></div></div>`,
  entry: { ...entry, selector: '[data-id="15635349"]' },
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-bold brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><strong style="font-weight: bold; ">Community Conversations - Tuesday’s at 12:00 - 1:15 pm</strong></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 400; ">This group practices using tools for building multicultural community that recognizes, understands, appreciates and utilizes differences at personal, interpersonal, institutional and cultural levels. The group is always open.</span><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 400; ">https://us02web.zoom.us/j/96076302991?pwd=eTlMNExGKzFKc2YwUDlTV0UvUkVQUT09</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Meeting ID: 960 7630 2991</span><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Passcode: 254042</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>One tap mobile</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>+19292056099,,96076302991#,,,,*254042#</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-bold brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-0_0"><strong style="font-weight: bold; ">Seekers Sunday School Class </strong><span style="">-</span><span style="">&nbsp;</span><u style=""><span>Sundays</span></u><span style="">, 9:30 am - 10:30 am via Zoom</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="">https://us02web.zoom.us/j/806127553?</span><span>pwd=YUw1V213OVJnUmpxUjAvS2l4ZUtuQT09</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="">Meeting ID: 806 127 553</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="">Password: seekers</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="">One tap mobile&nbsp; 19292056099,,806127553#,,,,*4838220#</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style=""><strong style=""><br></strong></span></p><p class="brz-fs-lg-0_94 brz-ff-lato brz-ft-upload brz-fw-lg-bold brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-0_0"><span style=""><strong style="font-weight: bold; ">Building the Beloved Community </strong>- Wednesdays 5:00 - 6:30 p.m. in person and by Zoom.&nbsp;</span><span style="">We will share a light meal and build the beloved community that Jesus makes possible - community where both our common humanity and our beautiful differences are recognized, understood and appreciated.&nbsp;</span><span>We will be accepting that gift of God offered to us in our baptism . . . the freedom and power to resist evil, injustice and oppression in whatever forms they present themselves.&nbsp;</span><span style="">Here is the Zoom link to join online:&nbsp;</span><a data-behavior="truncate" rel="noreferrer" style="" target="_blank" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fus02web.zoom.us%2Fj%2F81757191393%3Fpwd%3Dr0sscYwL949W_RJUtdBYwQvgT5T81S.1%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span>https://us02web.zoom.us/j/81757191393?pwd=r0sscYwL949W_RJUtdBYwQvgT5T81S.1</span></a></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Meeting ID: 817 5719 1393</span><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Passcode: 746729</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>One tap mobile</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>+19292056099,,81757191393#,,,,*746729#</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-bold brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-0"><span style=""><strong style="font-weight: bold; ">Prayer Circle</strong><span>&nbsp;</span>-<span>&nbsp;</span><u style=""><span>Tuesdays</span></u><span>&nbsp;</span>at 10 am, Wesley Room</span></p>'
              }
            }
          ]
        }
      }
    ]
  }
};

//#endregion

//#region Example 42 (Anthem text with img tag)
const ex42: Data = {
  html: `<div class="text-content text-1 editable" data-id="21313223" data-category="text"><div><p style="font-size: 0.8464em;"><a target="_blank" href="https://www.givelify.com/givenow/1.0/Mzg3MTM=/selection"><img src="https://images.givelify.com/PrimaryGiveButton2x.png" alt="Givelify" width="654" height="105"></a></p><div><br></div><div><br></div><div><br></div><p style="text-align: left; font-family: Montserrat, sans-serif; font-weight: 400; font-size: 1.3021em;">“For where your treasure is, there your heart will be also,” (<b style="">Matthew 6:21</b>)</p><p style="text-align: left; font-family: Montserrat, sans-serif; font-weight: 400; font-size: 1.3021em;"><br></p><p style="font-style: normal; font-weight: 400; letter-spacing: normal; text-align: start; font-family: Montserrat, sans-serif; font-size: 1.3021em;">At Walnut Grove Christian Church, we deeply appreciate and value your contributions. Your act of giving has a profound impact, not only on the church, but also on the lives of countless individuals. We want to express our sincere gratitude for your support.</p><p style="font-style: normal; font-weight: 400; letter-spacing: normal; text-align: start; font-family: Montserrat, sans-serif; font-size: 1.3021em;"><br></p><p style="text-align: left; font-family: Montserrat, sans-serif; font-weight: 400; font-size: 1.3021em;"><a target="_blank" href="https://www.givelify.com/givenow/1.0/Mzg3MTM=/selection"><img src="https://images.givelify.com/DarkGiveButton2x.png" alt="Givelify" width="654" height="105"></a><br></p><p style="text-align: left; font-size: 1.3672em;"><br></p></div></div>`,
  entry: { ...entry, selector: '[data-id="21313223"]' },
  output: {
    data: [
      {
        type: "Wrapper",
        value: {
          _id: "1",
          _styles: ["wrapper", "wrapper--image"],
          horizontalAlign: "",
          items: [
            {
              type: "Image",
              value: {
                alt: "Givelify",
                height: 0,
                heightSuffix: "px",
                imageSrc: "https://images.givelify.com/PrimaryGiveButton2x.png",
                linkExternal:
                  "https://www.givelify.com/givenow/1.0/Mzg3MTM=/selection",
                linkExternalBlank: "on",
                linkType: "external",
                sizeType: "custom",
                width: 0,
                widthSuffix: "px"
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
                text: '<p class="brz-fs-lg-0_85 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_3 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 400; ">“For where your treasure is, there your heart will be also,” (</span><b style=""><span style="font-weight: bold; ">Matthew 6:21</span></b><span style="font-weight: 400; ">)</span></p><p class="brz-fs-lg-1_3 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_3 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-0"><span style="font-weight: 400; ">At Walnut Grove Christian Church, we deeply appreciate and value your contributions. Your act of giving has a profound impact, not only on the church, but also on the lives of countless individuals. We want to express our sincere gratitude for your support.</span></p><p class="brz-fs-lg-1_3 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-0"><br></p>'
              }
            }
          ]
        }
      },
      {
        type: "Wrapper",
        value: {
          _id: "1",
          _styles: ["wrapper", "wrapper--image"],
          horizontalAlign: "",
          items: [
            {
              type: "Image",
              value: {
                alt: "Givelify",
                height: 0,
                heightSuffix: "px",
                imageSrc: "https://images.givelify.com/DarkGiveButton2x.png",
                linkExternal:
                  "https://www.givelify.com/givenow/1.0/Mzg3MTM=/selection",
                linkExternalBlank: "on",
                linkType: "external",
                sizeType: "custom",
                width: 0,
                widthSuffix: "px"
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
                text: '<p class="brz-fs-lg-1_3 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_37 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p>'
              }
            }
          ]
        }
      }
    ]
  }
};

//#endregion

//#region Example 43 (Anthem wrong color for fonts tag and wrong alignment for div tag)
const ex43: Data = {
  html: `<div class="text-content text-1 editable" data-id="14121848" data-category="text"><div><div id=":54t" style="font-size: small; line-height: 1.5;"><div dir="ltr"><div><div dir="ltr" data-smartmail="gmail_signature"><div dir="ltr"><div dir="ltr"><div><div><p><font color="#000000" face="Arial, sans-serif"><b><span class="clovercustom" style="font-size: 1.3077em;">Location: </span></b><span class="clovercustom" style="font-size: 1.3077em;">Living Waters Ranch in Challis, ID (Here's a link for you to check it out: </span></font><a href="https://livingwatersranch.org/" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://livingwatersranch.org&amp;source=gmail&amp;ust=1731558437935000&amp;usg=AOvVaw1XEoNQqQleUpvQhnP08EkX"><span class="clovercustom" style="font-size: 1.3077em;">https://livingwatersranch.org</span></a><span class="clovercustom" style="font-size: 1.3077em;">)</span><span class="clovercustom" style="font-size: 1.3077em;">&nbsp;</span></p><p><b><span class="clovercustom" style="font-size: 1.3077em;">Cost: </span></b><span class="clovercustom" style="font-size: 1.3077em;">$130 </span></p><p><b><span class="clovercustom" style="font-size: 1.3077em;">Deadline to Register: </span></b><span class="clovercustom" style="font-size: 1.3077em;">Monday, October 14th. After this, the cost goes up to $150 until October 21st.</span></p><div dir="ltr"><font color="#888888"><span class="clovercustom" style="font-size: 1.3077em;">Grace &amp; Peace,</span><div><span class="clovercustom" style="font-size: 1.3077em;">Rev. Val Soto</span><p><br></p><p><a href="mailto:val.soto@tfnaz.com" target="_blank"><span class="clovercustom" style="font-size: 1.3077em;">val.soto@tfnaz.com</span></a></p><p><br></p></div></font></div></div></div></div></div></div></div></div></div></div></div>`,
  entry: { ...entry, selector: '[data-id="14121848"]' },
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
                text: '<p class="brz-fs-lg-1_31 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(0, 0, 0); "><b><span style="">Location: </span></b><span style="color: rgb(0, 0, 0); ">Living Waters Ranch in Challis, ID (Here\'s a link for you to check it out: </span></span><a target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://livingwatersranch.org&amp;source=gmail&amp;ust=1731558437935000&amp;usg=AOvVaw1XEoNQqQleUpvQhnP08EkX" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Flivingwatersranch.org%2F%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span style="">https://livingwatersranch.org</span></a><span style="">)</span><span style="">&nbsp;</span></p><p class="brz-fs-lg-1_31 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><b><span style="">Cost: </span></b><span style="">$130 </span></p><p class="brz-fs-lg-1_31 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><b><span style="">Deadline to Register: </span></b><span style="">Monday, October 14th. After this, the cost goes up to $150 until October 21st.</span></p><p dir="ltr" class="brz-fs-lg-1_31 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(136, 136, 136); "><span style="color: rgb(136, 136, 136); ">Grace &amp; Peace,</span></span></p><p class="brz-fs-lg-1_31 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="">Rev. Val Soto</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_31 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><a target="_blank" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22mailto%3Aval.soto%40tfnaz.com%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span style="">val.soto@tfnaz.com</span></a></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p></p><p></p>'
              }
            }
          ]
        }
      }
    ]
  }
};
//#endregion

//#region Example 44 (Anthem strong tag is not bold)
const ex44: Data = {
  html: `<div class="text-content text-1 editable" data-id="8822884" data-category="text"><div><p style="font-weight: 400; font-size: 0.8644em;"><strong style="letter-spacing: normal;">VISION</strong><br style="letter-spacing: normal;"><span class="clovercustom" style="letter-spacing: normal;">Our vision is to create a church for people to discover and fulfil the love and purpose that God has for them. &nbsp;</span><span class="clovercustom" style="letter-spacing: normal;">We want to provide an environment where people can <span class="clovercustom" style="font-weight: 700; font-style: italic;">CONNECT</span> to God and others, provide opportunities</span><span class="clovercustom" style="letter-spacing: normal;"> to <span class="clovercustom" style="font-weight: 700; font-style: italic;">GROW</span> in their relationship with God and others, &amp; to <span class="clovercustom" style="font-style: italic; font-weight: 700;">SERVE</span> God and others, fulfilling their God given purpose.</span><br style="letter-spacing: normal;"><strong style="letter-spacing: normal;"><br></strong></p><p style="font-weight: 400; font-size: 0.8644em;"><span class="clovercustom" style="font-weight: 700;">MISSION</span><br style="letter-spacing: normal;"><span class="clovercustom" style="letter-spacing: normal;">Our mission is to help lead people into a growing relationship with Jesus Christ. &nbsp;</span><span class="clovercustom" style="letter-spacing: normal;">We want to provide a loving family environment that engages people and leads them across each step of their spiritual</span><span class="clovercustom" style="letter-spacing: normal;">&nbsp;</span><span class="clovercustom" style="letter-spacing: normal;">journey.&nbsp; We seek to accomplish this through our worship services, relational small groups, and outreach mission projects.</span><br style="letter-spacing: normal;"><span class="clovercustom" style="letter-spacing: normal;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span><br style="letter-spacing: normal;"><strong style="letter-spacing: normal;">WHO ARE WE?<br></strong><span class="clovercustom" style="letter-spacing: normal;">South West Baptist Church is an independent church and is not affiliated with any denomination.</span><span class="clovercustom" style="letter-spacing: normal;">&nbsp;</span><span class="clovercustom" style="letter-spacing: normal;"> We first met together in July 2012 as an outreach ministry of </span><a href="http://www.gospelbaptist.org.au/" target="_blank" style="letter-spacing: normal; color: rgb(105, 105, 107); font-weight: 700;">Gospel Baptist Church</a><span class="clovercustom" style="letter-spacing: normal;">, Wanneroo, WA.&nbsp;</span><br></p></div></div>`,
  entry: { ...entry, selector: '[data-id="8822884"]' },
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
                text: '<p class="brz-fs-lg-0_86 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-0"><strong style="font-weight: bold; ">VISION</strong><br style=""><span style="font-weight: 400; ">Our vision is to create a church for people to discover and fulfil the love and purpose that God has for them. &nbsp;</span><span style="font-weight: 400; ">We want to provide an environment where people can <em style="font-weight: 700; ">CONNECT</em> to God and others, provide opportunities</span><span style="font-weight: 400; "> to <em style="font-weight: 700; ">GROW</em> in their relationship with God and others, &amp; to <em style="font-weight: 700; ">SERVE</em> God and others, fulfilling their God given purpose.</span><br style=""><strong style=""><br></strong></p><p class="brz-fs-lg-0_86 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-0"><span style="font-weight: 700; ">MISSION</span><br style=""><span style="font-weight: 400; ">Our mission is to help lead people into a growing relationship with Jesus Christ. &nbsp;</span><span style="font-weight: 400; ">We want to provide a loving family environment that engages people and leads them across each step of their spiritual</span><span style="">&nbsp;</span><span style="font-weight: 400; ">journey.&nbsp; We seek to accomplish this through our worship services, relational small groups, and outreach mission projects.</span><br style=""><span style="">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span><br style=""><strong style="font-weight: bold; ">WHO ARE WE?<br></strong><span style="font-weight: 400; ">South West Baptist Church is an independent church and is not affiliated with any denomination.</span><span style="">&nbsp;</span><span style="font-weight: 400; "> We first met together in July 2012 as an outreach ministry of </span><a target="_blank" style="color: rgb(105, 105, 107); font-weight: 700; " data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22http%3A%2F%2Fwww.gospelbaptist.org.au%2F%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span style="color: rgb(105, 105, 107); font-weight: 700; ">Gospel Baptist Church</span></a><span style="font-weight: 400; ">, Wanneroo, WA.&nbsp;</span><br></p>'
              }
            }
          ]
        }
      }
    ]
  }
};
//#endregion

//#region Example 45 (Anthem trim spaces at the start of the tag)
const ex45: Data = {
  html: `<header class="text-content text-1 title-text editable" data-id="14236948" data-category="text"><div><p style="font-family: Antonio, sans-serif; font-weight: 700; color: rgb(5, 5, 5);">Don Thrasher</p><p style="font-family: Antonio, sans-serif; font-weight: 700; color: rgb(5, 5, 5);">Small Group Facilitator</p><p style="font-family: Antonio, sans-serif; font-weight: 700; color: rgb(5, 5, 5);">                             <style>    </style>        <a href="mailto:Thrashfish@gmail.com" style="font-size: 0.8247em;">Thrashfish@gmail.com</a>  &nbsp;<br></p></div></header>`,
  entry: { ...entry, selector: '[data-id="14236948"]' },
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(5, 5, 5); font-weight: 700; ">Don Thrasher</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(5, 5, 5); font-weight: 700; ">Small Group Facilitator</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"> <a style="" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22mailto%3AThrashfish%40gmail.com%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span>Thrashfish@gmail.com</span></a> &nbsp;<br></p>'
              }
            }
          ]
        }
      }
    ]
  }
};
//#endregion

//#region Example 46 (Anthem duplicate embeds models)
const ex46: Data = {
  html: `<div class="text-content text-1 editable" data-category="text" data-id="11222015"><div><p></p><div class="embedded-paste" contenteditable="false" data-src="<p><br></p><div class=&quot;embedded-paste&quot; contenteditable=&quot;true&quot;><script src=&quot;https://s3-us-west-2.amazonaws.com/bloomerang-public-cdn/bestwa/.widget-js/73728.js&quot; type=&quot;text/javascript&quot;></script></div><p><br></p>"><p><br></p><div class="embedded-paste" contenteditable="true"><script src="https://s3-us-west-2.amazonaws.com/bloomerang-public-cdn/bestwa/.widget-js/73728.js" type="text/javascript"></script><style text="text/css">.email-registration-form label{color: #404040;                display: block;}.email-registration-form label.error{color:#900;                display: inline-block;                 padding: 0 10px;}.email-registration-form .field{padding: 4px 0;}.email-registration-form .consent{padding-bottom: 4px;}.email-registration-form .field .required-star{color: #aa0000;                 display: inline-block;                 margin-left: 5px;}.email-registration-form .field .checkboxes{max-width:275px;                border: 1px solid #A9A9A9;                -webkit-transition: all .3s ease-out;                -moz-transition: all .3s ease-out;                transition: all .3s ease-out;}.email-registration-form .field .checkbox{display:block;                position:relative;                -moz-box-sizing:border-box;                box-sizing:border-box;                height:30px;                line-height:26px;                padding:2px 28px 2px 8px;                border-bottom:1px solid rgba(0,0,0,0.1);                color:#404040;                  overflow:hidden;                text-decoration:none; }.email-registration-form .field .checkbox input{opacity:0.01;                position:absolute;                left:-50px;                  z-index:-5;}.email-registration-form .field .checkbox:last-child{border-bottom:none;}.email-registration-form .field .checkbox.selected{background: rgb(50, 142, 253);                color:#fff; }.email-registration-form .field .checkbox.selected:before{color:#fff;                line-height:30px;                position:absolute;                right:10px; }.email-registration-form .field input{padding: 4px;                 width: 275px;}.email-registration-form .errors{border: 1px solid #900;                color: #900;                  padding: 10px;}.email-registration-form .hidden{display: none;}.btn-group .btn-submit-email{padding: 4px 10px;}input, select, textarea, button{font-family: inherit;}</style><div id="email-registration-form-container">  <form action="javascript:void(0)" class="email-registration-form" id="email-registration-form" method="post" novalidate="novalidate">    <div class="errors hidden"></div>    <div class="section contact">      <h3>Contact Information</h3>      <div class="field text first-name required"><label for="first-name"><span class="label">First Name</span><span class="required-star">*</span></label><input class="required" id="first-name" name="first-name" type="text"></div><div class="field text last-name required"><label for="last-name"><span class="label">Last Name</span><span class="required-star">*</span></label><input class="required" id="last-name" name="last-name" type="text"></div><div class="field email email-address required"><label for="email-address"><span class="label">Email</span><span class="required-star">*</span></label><input class="email required" id="email-address" name="email-address" placeholder="someone@website.com" type="email"></div><div class="consent">I want to receive emails at this address</div></div>            <div class="btn-group">      <input class="btn btn-submit btn-submit-email" type="submit" value="Sign up">    </div>  </form></div></div><p><br></p></div><br><p></p></div></div>`,
  entry: { ...entry, selector: '[data-id="11222015"]' },
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"></p>'
              }
            }
          ]
        }
      },
      {
        type: "EmbedCode"
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
                text: '<br><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"></p>'
              }
            }
          ]
        }
      }
    ]
  }
};
//#endregion

//#region Example 47 (Solstice separated icons with empty text)
const ex47: Data = {
  html: `<div class="text-content text-0 editable" data-id="820194" data-category="text"><div><p style="color: rgb(255, 255, 255);"><span class="clovercustom" style="letter-spacing: -0.2px;"><a class="socialIconLink cloverlinks" style="color: rgb(250, 245, 246);" href="mailto:info@thewordchurch.ca" data-location="email" data-button="false" data-detail="info@thewordchurch.ca" data-category="link" target="_self"><span data-socialicon=""><span class="socialIconSymbol" aria-hidden="true"></span><span class="sr-only"></span></span></a>  <a class="socialIconLink cloverlinks" href="https://www.youtube.com/channel/UC65LcaIE2wC2rVq24XFmhGw" data-location="external" data-detail="https://www.youtube.com/channel/UC65LcaIE2wC2rVq24XFmhGw" data-category="link" target="_blank" style="color: rgb(255, 255, 255);"><span data-socialicon="roundedyoutube"><span class="socialIconSymbol" aria-hidden="true"></span><span class="sr-only">roundedyoutube</span></span></a>  ​</span><a class="socialIconLink cloverlinks" style="color: rgb(245, 246, 250); letter-spacing: -0.2px;" href="https://www.facebook.com/wordchurchlloyd" data-location="external" data-button="false" data-detail="https://www.facebook.com/wordchurchlloyd" data-category="link" target="_blank"><span data-socialicon=""><span class="socialIconSymbol" aria-hidden="true"></span><span class="sr-only"></span></span></a> &nbsp;<a class="socialIconLink cloverlinks" href="https://www.instagram.com/word_church_lloyd/" data-location="external" data-detail="https://www.instagram.com/word_church_lloyd/" data-category="link" target="_blank" style="color: rgb(255, 255, 255);"><span data-socialicon="roundedinstagram"><span class="socialIconSymbol" aria-hidden="true"></span><span class="sr-only">roundedinstagram</span></span></a><span style="font-size: 0.96em; letter-spacing: -0.2px;">&nbsp;</span><a href="https://itunes.apple.com/ca/podcast/the-word-church-podcast/id421196163" data-location="external" data-detail="https://itunes.apple.com/ca/podcast/the-word-church-podcast/id421196163" data-category="link" target="_blank" class="cloverlinks" style="font-size: 0.96em; background-color: rgb(76, 76, 82); letter-spacing: -0.2px; color: rgb(255, 255, 255);"><span data-socialicon=""><span class="socialIconSymbol" aria-hidden="true"></span><span class="sr-only"></span></span>​</a><span style="font-size: 0.96em; letter-spacing: -0.01em;"> </span><span style="font-size: 0.96em; letter-spacing: -0.2px;"> </span></p><p style="color: rgb(255, 255, 255);"><br></p><p style="color: rgb(255, 255, 255); font-size: 1.4694em; font-weight: 600;">The Word Church</p><p style="color: rgb(255, 255, 255); font-weight: 300;">1802 49 Avenue</p><p style="color: rgb(255, 255, 255);">Lloydminster, SK S9V 1T2</p><p style="color: rgb(255, 255, 255);">306-825-9673</p><p style="color: rgb(255, 255, 255);">info@thewordchurch.ca</p></div></div>`,
  entry: { ...entry, selector: '[data-id="820194"]' },
  output: {
    data: [
      {
        type: "Cloneable",
        value: {
          _id: "1",
          _styles: ["wrapper-clone", "wrapper-clone--icon"],
          horizontalAlign: undefined,
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                customSize: 26,
                linkExternal: "mailto:info@thewordchurch.ca",
                linkExternalBlank: "on",
                linkType: "external",
                name: "envelope-square",
                padding: 7,
                type: "fa"
              }
            },
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                customSize: 26,
                linkExternal:
                  "https://www.youtube.com/channel/UC65LcaIE2wC2rVq24XFmhGw",
                linkExternalBlank: "on",
                linkType: "external",
                name: "youtube",
                padding: 7,
                type: "fa"
              }
            },
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                customSize: 26,
                linkExternal: "https://www.facebook.com/wordchurchlloyd",
                linkExternalBlank: "on",
                linkType: "external",
                name: "facebook-square",
                padding: 7,
                type: "fa"
              }
            },
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                customSize: 26,
                linkExternal: "https://www.instagram.com/word_church_lloyd/",
                linkExternalBlank: "on",
                linkType: "external",
                name: "instagram",
                padding: 7,
                type: "fa"
              }
            },
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                bgColorHex: "#4c4c52",
                bgColorOpacity: undefined,
                bgColorPalette: "",
                customSize: 26,
                linkExternal:
                  "https://itunes.apple.com/ca/podcast/the-word-church-podcast/id421196163",
                linkExternalBlank: "on",
                linkType: "external",
                name: "itunes",
                padding: 7,
                type: "fa"
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_47 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(255, 255, 255); font-weight: 600; ">The Word Church</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(255, 255, 255); font-weight: 300; ">1802 49 Avenue</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(255, 255, 255); ">Lloydminster, SK S9V 1T2</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(255, 255, 255); ">306-825-9673</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(255, 255, 255); ">info@thewordchurch.ca</span></p>'
              }
            }
          ]
        }
      }
    ]
  }
};
//#endregion

// Solstice example 48 (Solstice empty text)
const ex48: Data = {
  html: `<header class="text-content text-0 title-text editable" data-id="23914520" data-category="text"><div><p class="finaldraft_placeholder">Upcoming Events</p></div></header>`,
  entry: { ...entry, selector: '[data-id="23914520"]' },
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Upcoming Events</span></p>'
              }
            }
          ]
        }
      }
    ]
  }
};

// Majesty example 49 (Text content is missing in text with icons)
const ex49: Data = {
  html: `<div class="text-content text-2 editable" data-id="2564559" data-category="text"><div><p><a href="https://www.facebook.com/New-Life-Downtown-Hickory-161798330532473/" data-location="external" data-detail="https://www.facebook.com/New-Life-Downtown-Hickory-161798330532473/" data-category="link" target="_blank" class="cloverlinks" style="font-weight: 600; font-size: 1.125em; letter-spacing: 0em; background-color: rgb(249, 250, 247); color: rgb(141, 197, 63);"><span class="clovercustom" style="color: rgb(94, 94, 94);">To connect with us on </span><span data-socialicon="circlefacebook"><span class="socialIconSymbol" style="font-weight: 700;" aria-hidden="true"></span><span class="sr-only">circlefacebook</span></span> <span class="clovercustom"> click here </span></a></p><p style="color: rgb(141, 197, 63); font-weight: 600;"><br></p><p style="color: rgb(141, 197, 63); font-weight: 600;"><a href="https://www.instagram.com/newlifehky/" data-location="external" data-detail="https://www.instagram.com/newlifehky/" data-category="link" target="_blank" class="cloverlinks" style="color: rgb(141, 197, 63); font-size: 1.1111em;" data-button="false"><span class="clovercustom" style="color: rgb(94, 94, 94);">To connect with us on</span>&nbsp;&nbsp;<span data-icon="instagram"><span class="clovericons fab" aria-hidden="true"></span><span class="sr-only">Instagram</span></span> </a> <a style="font-size: 1.1111em;" href="https://www.instagram.com/newlifehky/" data-location="external" data-button="false" data-detail="https://www.instagram.com/newlifehky/" data-category="link" target="_blank" class="cloverlinks">click here</a></p></div></div>`,
  entry: { ...entry, selector: '[data-id="2564559"]' },
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><a data-location="external" data-detail="https://www.facebook.com/New-Life-Downtown-Hickory-161798330532473/" data-category="link" target="_blank" style="font-weight: 600; background-color: rgb(249, 250, 247); color: rgb(141, 197, 63); " data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fwww.facebook.com%2FNew-Life-Downtown-Hickory-161798330532473%2F%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span style="color: rgb(94, 94, 94); font-weight: 600; ">To connect with us on </span></a></p>'
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
          horizontalAlign: undefined,
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                bgColorHex: "#f9faf7",
                bgColorPalette: "",
                bgColorOpacity: undefined,
                customSize: 26,
                linkExternal:
                  "https://www.facebook.com/New-Life-Downtown-Hickory-161798330532473/",
                linkExternalBlank: "on",
                linkType: "external",
                name: "facebook-square",
                padding: 7,
                type: "fa"
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><a data-location="external" data-detail="https://www.facebook.com/New-Life-Downtown-Hickory-161798330532473/" data-category="link" target="_blank" style="font-weight: 600; background-color: rgb(249, 250, 247); color: rgb(141, 197, 63); " data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fwww.facebook.com%2FNew-Life-Downtown-Hickory-161798330532473%2F%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span style="color: rgb(141, 197, 63); font-weight: 600; "> click here </span></a></p>'
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p>'
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><a data-location="external" data-detail="https://www.instagram.com/newlifehky/" data-category="link" target="_blank" style="color: rgb(141, 197, 63); " data-button="false" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fwww.instagram.com%2Fnewlifehky%2F%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span style="color: rgb(94, 94, 94); ">To connect with us on</span></a></p>'
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
          horizontalAlign: undefined,
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                customSize: 26,
                linkExternal: "https://www.instagram.com/newlifehky/",
                linkExternalBlank: "on",
                linkType: "external",
                name: "instagram",
                padding: 7,
                type: "fa"
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
                text: '<p class="brz-fs-lg-1_11 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><a style="" data-location="external" data-button="false" data-detail="https://www.instagram.com/newlifehky/" data-category="link" target="_blank" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fwww.instagram.com%2Fnewlifehky%2F%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span>click here</span></a></p>'
              }
            }
          ]
        }
      }
    ]
  }
};

// Majesty example 50 (Svg icon inside text)
const ex50: Data = {
  html: `<div class="text-content text-1 editable" data-id="19633956" data-category="text"><div><p style="margin-top:-150px;"><!--?xml version="1.0" encoding="UTF-8" standalone="no"?--><svg xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:cc="http://creativecommons.org/ns#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" contentscripttype="text/ecmascript" width="184.44531" zoomAndPan="magnify" contentstyletype="text/css" viewBox="0 0 184.44531 125.82422" height="125.82422" preserveAspectRatio="xMidYMid meet" version="1.0" id="svg497" sodipodi:docname="Campaign Logo White SVG.svg" inkscape:version="1.0.2 (e86c870879, 2021-01-15)" style="">  <metadata id="metadata501">    <rdf:rdf>      <cc:work rdf:about="">        <dc:format>image/svg+xml</dc:format>        <dc:type rdf:resource="http://purl.org/dc/dcmitype/StillImage"></dc:type>        <dc:title></dc:title>      </cc:work>    </rdf:rdf>  </metadata>  <sodipodi:namedview pagecolor="#ffffff" bordercolor="#666666" borderopacity="1" objecttolerance="10" gridtolerance="10" guidetolerance="10" inkscape:pageopacity="0" inkscape:pageshadow="2" inkscape:window-width="1920" inkscape:window-height="1016" id="namedview499" showgrid="false" fit-margin-top="0" fit-margin-left="0" fit-margin-right="0" fit-margin-bottom="0" inkscape:zoom="2.0822542" inkscape:cx="94.868605" inkscape:cy="106.37725" inkscape:window-x="0" inkscape:window-y="27" inkscape:window-maximized="1" inkscape:current-layer="svg497" inkscape:document-rotation="0"></sodipodi:namedview>  <defs id="defs156">    <g id="g145">      <g id="glyph-0-0"></g>      <g id="glyph-0-1">        <path d="M 3.65625,-13.34375 V 0 H 2.046875 V -16.328125 H 3.28125 l 10.8125,13.59375 V -16.3125 h 1.609375 V 0 H 14.3125 Z m 0,0" id="path9"></path>      </g>      <g id="glyph-0-2">        <path d="M 13.015625,-1.421875 V 0 H 2.046875 V -16.328125 H 12.8125 v 1.421875 H 3.65625 v 5.890625 h 7.984375 V -7.65625 H 3.65625 v 6.234375 z m 0,0" id="path12"></path>      </g>      <g id="glyph-0-3">        <path d="M 8.1875,-16.21875 H 9.703125 L 12,-10.421875 14.328125,-16.21875 H 15.875 l -2.859375,6.96875 3.125,7.390625 5.921875,-14.46875 h 1.75 L 16.875,0 H 15.453125 L 12.03125,-8.078125 8.578125,0 H 7.171875 L 0.28125,-16.328125 H 2 L 7.9375,-1.859375 11.046875,-9.25 Z m 0,0" id="path15"></path>      </g>      <g id="glyph-0-4"></g>      <g id="glyph-0-5">        <path d="m 11.59375,-13.265625 c -0.460938,-0.519531 -1.074219,-0.941406 -1.84375,-1.265625 -0.761719,-0.320312 -1.636719,-0.484375 -2.625,-0.484375 -1.4375,0 -2.492188,0.273437 -3.15625,0.8125 -0.65625,0.542969 -0.984375,1.28125 -0.984375,2.21875 0,0.492187 0.085937,0.898437 0.265625,1.21875 0.175781,0.3125 0.445312,0.585937 0.8125,0.8125 0.375,0.230469 0.847656,0.429687 1.421875,0.59375 0.570313,0.167969 1.25,0.335937 2.03125,0.5 0.875,0.1875 1.660156,0.398437 2.359375,0.625 0.695312,0.230469 1.289062,0.515625 1.78125,0.859375 0.488281,0.34375 0.863281,0.765625 1.125,1.265625 0.257812,0.492187 0.390625,1.101563 0.390625,1.828125 0,0.75 -0.148437,1.402344 -0.4375,1.953125 -0.28125,0.554687 -0.6875,1.015625 -1.21875,1.390625 C 10.992188,-0.570312 10.375,-0.300781 9.65625,-0.125 8.9375,0.0507812 8.148438,0.140625 7.296875,0.140625 c -2.53125,0 -4.71875,-0.789063 -6.5625,-2.375 l 0.8125,-1.3125 C 1.835938,-3.234375 2.1875,-2.9375 2.59375,-2.65625 3,-2.382812 3.445312,-2.144531 3.9375,-1.9375 c 0.5,0.199219 1.03125,0.359375 1.59375,0.484375 0.570312,0.117187 1.175781,0.171875 1.8125,0.171875 1.300781,0 2.316406,-0.234375 3.046875,-0.703125 0.726563,-0.46875 1.09375,-1.175781 1.09375,-2.125 0,-0.507813 -0.105469,-0.9375 -0.3125,-1.28125 -0.210937,-0.34375 -0.523437,-0.640625 -0.9375,-0.890625 C 9.816406,-6.539062 9.300781,-6.757812 8.6875,-6.9375 8.082031,-7.125 7.375,-7.3125 6.5625,-7.5 5.695312,-7.695312 4.941406,-7.898438 4.296875,-8.109375 3.648438,-8.328125 3.101562,-8.597656 2.65625,-8.921875 c -0.4375,-0.320313 -0.773438,-0.707031 -1,-1.15625 C 1.4375,-10.535156 1.328125,-11.09375 1.328125,-11.75 c 0,-0.75 0.140625,-1.421875 0.421875,-2.015625 0.289062,-0.59375 0.695312,-1.082031 1.21875,-1.46875 0.519531,-0.394531 1.128906,-0.691406 1.828125,-0.890625 0.707031,-0.207031 1.492187,-0.3125 2.359375,-0.3125 1.082031,0 2.050781,0.167969 2.90625,0.5 0.851562,0.324219 1.625,0.78125 2.3125,1.375 z m 0,0" id="path19"></path>      </g>      <g id="glyph-0-6">        <path d="M 7.109375,-16.328125 H 8.46875 L 15.25,0 H 13.546875 L 11.4375,-5.109375 H 4.09375 L 2,0 H 0.28125 Z M 11.0625,-6.375 7.78125,-14.4375 4.4375,-6.375 Z m 0,0" id="path22"></path>      </g>      <g id="glyph-0-7">        <path d="m 0.96875,-8.28125 c 0,-0.976562 0.171875,-1.953125 0.515625,-2.921875 0.34375,-0.976563 0.84375,-1.847656 1.5,-2.609375 0.664063,-0.769531 1.476563,-1.394531 2.4375,-1.875 0.96875,-0.488281 2.066406,-0.734375 3.296875,-0.734375 1.457031,0 2.703125,0.335937 3.734375,1 1.03125,0.65625 1.796875,1.511719 2.296875,2.5625 l -1.265625,0.78125 c -0.261719,-0.53125 -0.585937,-0.976563 -0.96875,-1.34375 -0.375,-0.375 -0.777344,-0.675781 -1.203125,-0.90625 -0.429688,-0.226563 -0.871094,-0.390625 -1.328125,-0.484375 -0.460937,-0.101562 -0.914063,-0.15625 -1.359375,-0.15625 -0.980469,0 -1.84375,0.199219 -2.59375,0.59375 -0.75,0.398438 -1.386719,0.917969 -1.90625,1.5625 -0.511719,0.648438 -0.898438,1.371094 -1.15625,2.171875 -0.261719,0.804687 -0.390625,1.621094 -0.390625,2.453125 0,0.90625 0.15625,1.777344 0.46875,2.609375 0.3125,0.824219 0.738281,1.554687 1.28125,2.1875 0.550781,0.636719 1.195313,1.140625 1.9375,1.515625 0.75,0.375 1.554687,0.5625 2.421875,0.5625 0.46875,0 0.941406,-0.054688 1.421875,-0.171875 0.476563,-0.113281 0.941406,-0.296875 1.390625,-0.546875 0.457031,-0.257812 0.878906,-0.578125 1.265625,-0.953125 0.382813,-0.375 0.707031,-0.828125 0.96875,-1.359375 l 1.328125,0.6875 c -0.261719,0.617188 -0.632812,1.15625 -1.109375,1.625 -0.46875,0.46875 -0.996094,0.867188 -1.578125,1.1875 -0.585938,0.3125 -1.203125,0.558594 -1.859375,0.734375 -0.648437,0.1640625 -1.28125,0.25 -1.90625,0.25 -1.125,0 -2.15625,-0.242187 -3.09375,-0.734375 -0.9375,-0.488281 -1.746094,-1.128906 -2.421875,-1.921875 -0.667969,-0.789063 -1.1875,-1.6875 -1.5625,-2.6875 -0.375,-1.007813 -0.5625,-2.035156 -0.5625,-3.078125 z m 0,0" id="path25"></path>      </g>      <g id="glyph-0-8">        <path d="M 13.640625,-14.90625 H 7.8125 V 0 H 6.203125 v -14.90625 h -5.8125 v -1.421875 h 13.25 z m 0,0" id="path28"></path>      </g>      <g id="glyph-0-9">        <path d="m 8.671875,-1.328125 c 1.03125,0 1.882813,-0.191406 2.5625,-0.578125 0.6875,-0.394531 1.234375,-0.910156 1.640625,-1.546875 0.40625,-0.644531 0.6875,-1.375 0.84375,-2.1875 0.164062,-0.8125 0.25,-1.628906 0.25,-2.453125 v -8.234375 h 1.609375 v 8.234375 c 0,1.105469 -0.125,2.152344 -0.375,3.140625 -0.25,0.992187 -0.648437,1.859375 -1.1875,2.609375 -0.542969,0.75 -1.25,1.351562 -2.125,1.796875 -0.875,0.4375 -1.945313,0.65625 -3.203125,0.65625 -1.28125,0 -2.367188,-0.226563 -3.25,-0.6875 C 4.550781,-1.046875 3.835938,-1.664062 3.296875,-2.4375 2.765625,-3.207031 2.382812,-4.082031 2.15625,-5.0625 1.925781,-6.039062 1.8125,-7.050781 1.8125,-8.09375 v -8.234375 h 1.59375 v 8.234375 c 0,0.855469 0.082031,1.6875 0.25,2.5 0.164062,0.8125 0.453125,1.539062 0.859375,2.171875 0.40625,0.625 0.945313,1.132813 1.625,1.515625 0.675781,0.386719 1.519531,0.578125 2.53125,0.578125 z m 0,0" id="path31"></path>      </g>      <g id="glyph-0-10">        <path d="m 2.046875,0 v -16.328125 h 6.90625 c 0.695313,0 1.34375,0.152344 1.9375,0.453125 0.59375,0.292969 1.101563,0.683594 1.53125,1.171875 0.425781,0.480469 0.757813,1.027344 1,1.640625 0.238281,0.617188 0.359375,1.234375 0.359375,1.859375 0,0.605469 -0.08984,1.179687 -0.265625,1.71875 -0.179687,0.542969 -0.421875,1.03125 -0.734375,1.46875 -0.3125,0.4375 -0.695312,0.808594 -1.140625,1.109375 -0.449219,0.292969 -0.949219,0.496094 -1.5,0.609375 l 4,6.296875 h -1.8125 L 8.484375,-6.03125 H 3.65625 V 0 Z M 3.65625,-7.453125 H 9 c 0.46875,0 0.894531,-0.101563 1.28125,-0.3125 0.394531,-0.207031 0.726562,-0.484375 1,-0.828125 0.28125,-0.351562 0.5,-0.753906 0.65625,-1.203125 0.15625,-0.445313 0.234375,-0.914063 0.234375,-1.40625 0,-0.488281 -0.08984,-0.957031 -0.265625,-1.40625 -0.179688,-0.457031 -0.417969,-0.851563 -0.71875,-1.1875 -0.304688,-0.34375 -0.65625,-0.613281 -1.0625,-0.8125 C 9.71875,-14.804688 9.296875,-14.90625 8.859375,-14.90625 H 3.65625 Z m 0,0" id="path34"></path>      </g>      <g id="glyph-0-11">        <path d="m 2.140625,-16.328125 5.328125,8.78125 5.390625,-8.78125 h 1.75 l -6.328125,10.25 V 0 H 6.671875 V -6.125 L 0.375,-16.328125 Z m 0,0" id="path37"></path>      </g>      <g id="glyph-0-12">        <path d="M 16.515625,0 V -13.40625 L 10.5625,-3.03125 H 9.609375 L 3.65625,-13.40625 V 0 H 2.046875 v -16.328125 h 1.65625 l 6.375,11.171875 6.40625,-11.171875 H 18.125 V 0 Z m 0,0" id="path40"></path>      </g>      <g id="glyph-0-13">        <path d="M 2.046875,0 V -16.328125 H 8.8125 c 0.695312,0 1.34375,0.152344 1.9375,0.453125 0.59375,0.292969 1.101562,0.683594 1.53125,1.171875 0.425781,0.480469 0.757812,1.027344 1,1.640625 0.25,0.617188 0.375,1.234375 0.375,1.859375 0,0.679687 -0.117188,1.328125 -0.34375,1.953125 -0.230469,0.617188 -0.554688,1.164062 -0.96875,1.640625 -0.40625,0.46875 -0.902344,0.851563 -1.484375,1.140625 -0.574219,0.292969 -1.210937,0.4375 -1.90625,0.4375 H 3.65625 V 0 Z M 3.65625,-7.453125 H 8.875 c 0.476562,0 0.910156,-0.101563 1.296875,-0.3125 0.394531,-0.207031 0.726563,-0.484375 1,-0.828125 0.28125,-0.34375 0.492187,-0.738281 0.640625,-1.1875 0.15625,-0.457031 0.234375,-0.929688 0.234375,-1.421875 0,-0.5 -0.08984,-0.972656 -0.265625,-1.421875 -0.179688,-0.457031 -0.417969,-0.851562 -0.71875,-1.1875 -0.292969,-0.34375 -0.640625,-0.609375 -1.046875,-0.796875 -0.40625,-0.195313 -0.835937,-0.296875 -1.28125,-0.296875 H 3.65625 Z m 0,0" id="path43"></path>      </g>      <g id="glyph-0-14">        <path d="M 2.046875,0 V -16.328125 H 3.65625 V 0 Z m 0,0" id="path46"></path>      </g>      <g id="glyph-0-15">        <path d="m 13.84375,-2.390625 c -1.523438,1.667969 -3.265625,2.5 -5.234375,2.5 -1.105469,0 -2.125,-0.234375 -3.0625,-0.703125 C 4.617188,-1.070312 3.8125,-1.703125 3.125,-2.484375 2.445312,-3.265625 1.914062,-4.148438 1.53125,-5.140625 1.15625,-6.128906 0.96875,-7.144531 0.96875,-8.1875 c 0,-1.09375 0.1875,-2.132812 0.5625,-3.125 0.375,-1 0.894531,-1.878906 1.5625,-2.640625 0.675781,-0.757813 1.472656,-1.363281 2.390625,-1.8125 0.925781,-0.445313 1.9375,-0.671875 3.03125,-0.671875 0.789063,0 1.515625,0.08984 2.171875,0.265625 0.664062,0.167969 1.253906,0.414063 1.765625,0.734375 0.507813,0.3125 0.953125,0.6875 1.328125,1.125 0.382812,0.4375 0.707031,0.917969 0.96875,1.4375 l -1.25,0.828125 C 12.96875,-13.054688 12.269531,-13.800781 11.40625,-14.28125 10.550781,-14.757812 9.578125,-15 8.484375,-15 c -0.90625,0 -1.726563,0.195312 -2.453125,0.578125 -0.730469,0.386719 -1.351562,0.898437 -1.859375,1.53125 -0.511719,0.636719 -0.90625,1.367187 -1.1875,2.1875 -0.273437,0.824219 -0.40625,1.667969 -0.40625,2.53125 0,0.929687 0.15625,1.808594 0.46875,2.640625 0.3125,0.824219 0.742187,1.546875 1.296875,2.171875 0.550781,0.617187 1.195312,1.109375 1.9375,1.484375 0.75,0.367188 1.5625,0.546875 2.4375,0.546875 0.9375,0 1.828125,-0.21875 2.671875,-0.65625 0.851563,-0.4375 1.671875,-1.132813 2.453125,-2.09375 V -6.78125 h -3.625 v -1.25 h 4.984375 V 0 H 13.84375 Z m 0,0" id="path49"></path>      </g>      <g id="glyph-3-0"></g>      <g id="glyph-3-1">        <path d="m 7.125,-14.09375 h 1.3125 l 2,5.03125 2.015625,-5.03125 h 1.34375 L 11.3125,-8.046875 14.046875,-1.625 19.1875,-14.203125 h 1.515625 L 14.6875,0 h -1.25 L 10.453125,-7.015625 7.453125,0 h -1.21875 l -6,-14.203125 h 1.5 L 6.90625,-1.625 9.59375,-8.046875 Z m 0,0" id="path53"></path>      </g>      <g id="glyph-3-2">        <path d="m 4.125,0.203125 c -0.5,0 -0.960938,-0.085937 -1.375,-0.25 C 2.332031,-0.210938 1.96875,-0.441406 1.65625,-0.734375 1.34375,-1.035156 1.097656,-1.378906 0.921875,-1.765625 0.742188,-2.160156 0.65625,-2.585938 0.65625,-3.046875 c 0,-0.445313 0.101562,-0.863281 0.3125,-1.25 0.21875,-0.382813 0.519531,-0.71875 0.90625,-1 0.382812,-0.28125 0.84375,-0.5 1.375,-0.65625 0.539062,-0.15625 1.128906,-0.234375 1.765625,-0.234375 0.53125,0 1.066406,0.046875 1.609375,0.140625 0.550781,0.09375 1.039062,0.230469 1.46875,0.40625 v -0.9375 c 0,-0.914063 -0.261719,-1.644531 -0.78125,-2.1875 -0.511719,-0.539063 -1.230469,-0.8125 -2.15625,-0.8125 C 4.625,-9.578125 4.078125,-9.46875 3.515625,-9.25 2.960938,-9.039062 2.394531,-8.738281 1.8125,-8.34375 L 1.34375,-9.234375 c 1.34375,-0.90625 2.648438,-1.359375 3.921875,-1.359375 1.300781,0 2.320313,0.367188 3.0625,1.09375 0.75,0.730469 1.125,1.746094 1.125,3.046875 v 4.6875 c 0,0.375 0.164063,0.5625 0.5,0.5625 V 0 C 9.734375,0.0390625 9.5625,0.0625 9.4375,0.0625 9.09375,0.0625 8.820312,-0.0195312 8.625,-0.1875 8.4375,-0.363281 8.332031,-0.609375 8.3125,-0.921875 l -0.03125,-0.8125 c -0.480469,0.625 -1.085938,1.105469 -1.8125,1.4375 -0.730469,0.3320312 -1.511719,0.5 -2.34375,0.5 z M 4.4375,-0.84375 c 0.707031,0 1.359375,-0.128906 1.953125,-0.390625 0.59375,-0.269531 1.039063,-0.625 1.34375,-1.0625 C 7.859375,-2.421875 7.945312,-2.550781 8,-2.6875 8.0625,-2.832031 8.09375,-2.960938 8.09375,-3.078125 V -4.78125 C 7.644531,-4.957031 7.175781,-5.085938 6.6875,-5.171875 6.195312,-5.265625 5.703125,-5.3125 5.203125,-5.3125 c -0.960937,0 -1.742187,0.199219 -2.34375,0.59375 -0.605469,0.398438 -0.90625,0.921875 -0.90625,1.578125 0,0.324219 0.0625,0.625 0.1875,0.90625 0.132813,0.28125 0.3125,0.527344 0.53125,0.734375 0.21875,0.199219 0.476563,0.359375 0.78125,0.484375 0.3125,0.117187 0.640625,0.171875 0.984375,0.171875 z m 0,0" id="path56"></path>      </g>      <g id="glyph-3-3">        <path d="m 2.09375,3.203125 c 0.113281,0.00781 0.21875,0.015625 0.3125,0.015625 0.101562,0.00781 0.203125,0.015625 0.296875,0.015625 0.238281,0 0.425781,-0.03125 0.5625,-0.09375 0.144531,-0.0625 0.28125,-0.210937 0.40625,-0.4375 C 3.804688,2.472656 3.957031,2.144531 4.125,1.71875 4.289062,1.289062 4.53125,0.71875 4.84375,0 L 0.296875,-10.421875 h 1.40625 l 3.859375,9.15625 3.578125,-9.15625 H 10.4375 l -5.390625,13.4375 c -0.15625,0.382813 -0.40625,0.71875 -0.75,1 -0.335937,0.289063 -0.792969,0.4375 -1.375,0.4375 -0.136719,0 -0.265625,-0.00781 -0.390625,-0.015625 -0.117188,0 -0.261719,-0.011719 -0.4375,-0.03125 z m 0,0" id="path59"></path>      </g>      <g id="glyph-3-4">        <path d="m 4.953125,0.203125 c -0.8125,0 -1.605469,-0.1328125 -2.375,-0.390625 -0.773437,-0.269531 -1.4375,-0.660156 -2,-1.171875 l 0.625,-0.921875 c 0.582031,0.480469 1.175781,0.839844 1.78125,1.078125 C 3.597656,-0.960938 4.25,-0.84375 4.9375,-0.84375 c 0.84375,0 1.515625,-0.164062 2.015625,-0.5 0.5,-0.34375 0.75,-0.828125 0.75,-1.453125 0,-0.289063 -0.070313,-0.53125 -0.203125,-0.71875 C 7.363281,-3.710938 7.164062,-3.882812 6.90625,-4.03125 6.644531,-4.1875 6.316406,-4.316406 5.921875,-4.421875 5.535156,-4.535156 5.082031,-4.65625 4.5625,-4.78125 3.957031,-4.925781 3.4375,-5.070312 3,-5.21875 2.5625,-5.363281 2.195312,-5.53125 1.90625,-5.71875 1.625,-5.90625 1.410156,-6.132812 1.265625,-6.40625 1.128906,-6.675781 1.0625,-7.019531 1.0625,-7.4375 c 0,-0.519531 0.101562,-0.976562 0.3125,-1.375 0.207031,-0.40625 0.484375,-0.738281 0.828125,-1 0.351563,-0.257812 0.765625,-0.453125 1.234375,-0.578125 0.476562,-0.132813 0.984375,-0.203125 1.515625,-0.203125 0.800781,0 1.53125,0.132812 2.1875,0.390625 0.65625,0.261719 1.179687,0.601563 1.578125,1.015625 L 8.0625,-8.375 C 7.675781,-8.78125 7.203125,-9.082031 6.640625,-9.28125 c -0.554687,-0.195312 -1.125,-0.296875 -1.71875,-0.296875 -0.367187,0 -0.703125,0.039063 -1.015625,0.109375 C 3.601562,-9.394531 3.335938,-9.273438 3.109375,-9.109375 2.878906,-8.953125 2.695312,-8.75 2.5625,-8.5 c -0.136719,0.242188 -0.203125,0.527344 -0.203125,0.859375 0,0.28125 0.046875,0.511719 0.140625,0.6875 0.09375,0.167969 0.242188,0.320313 0.453125,0.453125 0.21875,0.125 0.488281,0.242188 0.8125,0.34375 0.332031,0.09375 0.726563,0.195312 1.1875,0.296875 0.664063,0.15625 1.253906,0.320313 1.765625,0.484375 0.507812,0.15625 0.929688,0.34375 1.265625,0.5625 0.34375,0.210938 0.597656,0.46875 0.765625,0.78125 0.175781,0.304688 0.265625,0.671875 0.265625,1.109375 0,0.960937 -0.371094,1.71875 -1.109375,2.28125 -0.730469,0.5625 -1.714844,0.84375 -2.953125,0.84375 z m 0,0" id="path62"></path>      </g>      <g id="glyph-3-5">        <path d="M 1.5,0 V -10.421875 H 2.859375 V 0 Z m 0,-12.59375 v -2 h 1.359375 v 2 z m 0,0" id="path65"></path>      </g>      <g id="glyph-3-6">        <path d="m 5.78125,0.203125 c -0.71875,0 -1.386719,-0.1523438 -2,-0.453125 -0.617188,-0.300781 -1.140625,-0.695312 -1.578125,-1.1875 -0.4375,-0.5 -0.78125,-1.070312 -1.03125,-1.71875 -0.25,-0.644531 -0.375,-1.320312 -0.375,-2.03125 0,-0.726562 0.117187,-1.421875 0.359375,-2.078125 C 1.394531,-7.929688 1.722656,-8.507812 2.140625,-9 c 0.425781,-0.488281 0.925781,-0.875 1.5,-1.15625 0.582031,-0.289062 1.222656,-0.4375 1.921875,-0.4375 0.875,0 1.648438,0.230469 2.328125,0.6875 0.6875,0.460938 1.222656,1.015625 1.609375,1.671875 v -6.359375 h 1.359375 v 12.828125 c 0,0.375 0.160156,0.5625 0.484375,0.5625 V 0 c -0.199219,0.0390625 -0.359375,0.0625 -0.484375,0.0625 -0.324219,0 -0.605469,-0.1015625 -0.84375,-0.3125 C 9.773438,-0.457031 9.65625,-0.707031 9.65625,-1 V -2.015625 C 9.25,-1.335938 8.691406,-0.796875 7.984375,-0.390625 7.273438,0.00390625 6.539062,0.203125 5.78125,0.203125 Z M 6.078125,-1 c 0.332031,0 0.6875,-0.066406 1.0625,-0.203125 C 7.523438,-1.335938 7.882812,-1.519531 8.21875,-1.75 8.550781,-1.976562 8.832031,-2.25 9.0625,-2.5625 9.300781,-2.882812 9.445312,-3.222656 9.5,-3.578125 v -3.21875 C 9.363281,-7.160156 9.171875,-7.5 8.921875,-7.8125 8.671875,-8.125 8.378906,-8.398438 8.046875,-8.640625 7.722656,-8.878906 7.375,-9.066406 7,-9.203125 6.625,-9.335938 6.257812,-9.40625 5.90625,-9.40625 c -0.5625,0 -1.074219,0.121094 -1.53125,0.359375 -0.449219,0.242187 -0.839844,0.558594 -1.171875,0.953125 -0.324219,0.398438 -0.574219,0.851562 -0.75,1.359375 -0.167969,0.5 -0.25,1.015625 -0.25,1.546875 0,0.5625 0.097656,1.101562 0.296875,1.609375 C 2.695312,-3.066406 2.96875,-2.625 3.3125,-2.25 3.664062,-1.875 4.078125,-1.570312 4.546875,-1.34375 5.023438,-1.113281 5.535156,-1 6.078125,-1 Z m 0,0" id="path68"></path>      </g>      <g id="glyph-3-7">        <path d="M 6,0.203125 C 5.238281,0.203125 4.539062,0.0625 3.90625,-0.21875 3.269531,-0.507812 2.71875,-0.90625 2.25,-1.40625 1.789062,-1.90625 1.429688,-2.484375 1.171875,-3.140625 0.910156,-3.796875 0.78125,-4.492188 0.78125,-5.234375 0.78125,-5.972656 0.910156,-6.664062 1.171875,-7.3125 1.429688,-7.957031 1.789062,-8.519531 2.25,-9 c 0.457031,-0.488281 1.003906,-0.875 1.640625,-1.15625 0.644531,-0.289062 1.34375,-0.4375 2.09375,-0.4375 0.757813,0 1.453125,0.148438 2.078125,0.4375 0.632812,0.28125 1.175781,0.667969 1.625,1.15625 0.457031,0.492188 0.8125,1.058594 1.0625,1.703125 0.257812,0.636719 0.390625,1.3125 0.390625,2.03125 0,0.117187 0,0.226563 0,0.328125 0,0.105469 -0.0078,0.179688 -0.01563,0.21875 h -8.92187 c 0.039063,0.5625 0.171875,1.085938 0.390625,1.5625 0.21875,0.46875 0.5,0.875 0.84375,1.21875 0.34375,0.34375 0.738281,0.617188 1.1875,0.8125 0.445312,0.1875 0.925781,0.28125 1.4375,0.28125 0.332031,0 0.664062,-0.046875 1,-0.140625 0.332031,-0.09375 0.640625,-0.21875 0.921875,-0.375 0.28125,-0.15625 0.53125,-0.347656 0.75,-0.578125 0.226563,-0.238281 0.40625,-0.5 0.53125,-0.78125 l 1.171875,0.3125 c -0.15625,0.398438 -0.386719,0.75 -0.6875,1.0625 -0.292969,0.3125 -0.632812,0.585938 -1.015625,0.8125 C 8.347656,-0.300781 7.921875,-0.125 7.453125,0 6.992188,0.132812 6.507812,0.203125 6,0.203125 Z m 3.84375,-5.96875 C 9.800781,-6.316406 9.671875,-6.820312 9.453125,-7.28125 9.234375,-7.75 8.953125,-8.148438 8.609375,-8.484375 8.265625,-8.816406 7.867188,-9.078125 7.421875,-9.265625 6.984375,-9.453125 6.507812,-9.546875 6,-9.546875 c -0.511719,0 -0.996094,0.09375 -1.453125,0.28125 -0.449219,0.1875 -0.84375,0.449219 -1.1875,0.78125 C 3.023438,-8.148438 2.753906,-7.75 2.546875,-7.28125 2.335938,-6.8125 2.21875,-6.304688 2.1875,-5.765625 Z m 0,0" id="path71"></path>      </g>      <g id="glyph-3-8"></g>      <g id="glyph-3-9">        <path d="m 1.78125,0 v -14.203125 h 5.875 c 0.613281,0 1.175781,0.132813 1.6875,0.390625 0.519531,0.261719 0.960938,0.605469 1.328125,1.03125 0.375,0.417969 0.664063,0.890625 0.875,1.421875 0.21875,0.53125 0.328125,1.074219 0.328125,1.625 0,0.585937 -0.101562,1.148437 -0.296875,1.6875 -0.199219,0.542969 -0.480469,1.023437 -0.84375,1.4375 -0.355469,0.40625 -0.78125,0.742187 -1.28125,1 -0.5,0.25 -1.058594,0.375 -1.671875,0.375 H 3.1875 V 0 Z M 3.1875,-6.484375 h 4.53125 c 0.414062,0 0.789062,-0.085937 1.125,-0.265625 0.34375,-0.175781 0.632812,-0.414062 0.875,-0.71875 0.238281,-0.300781 0.425781,-0.644531 0.5625,-1.03125 0.132812,-0.394531 0.203125,-0.804688 0.203125,-1.234375 0,-0.445313 -0.07813,-0.863281 -0.234375,-1.25 -0.15625,-0.394531 -0.367188,-0.738281 -0.625,-1.03125 -0.261719,-0.289063 -0.570312,-0.519531 -0.921875,-0.6875 -0.34375,-0.164063 -0.714844,-0.25 -1.109375,-0.25 H 3.1875 Z m 0,0" id="path75"></path>      </g>      <g id="glyph-3-10">        <path d="m 6.703125,-9.21875 c -0.929687,0.023438 -1.730469,0.277344 -2.40625,0.765625 -0.679687,0.480469 -1.15625,1.136719 -1.4375,1.96875 V 0 H 1.5 v -10.421875 h 1.28125 v 2.5 c 0.382812,-0.800781 0.898438,-1.425781 1.546875,-1.875 0.65625,-0.457031 1.359375,-0.6875 2.109375,-0.6875 0.101562,0 0.191406,0.01172 0.265625,0.03125 z m 0,0" id="path78"></path>      </g>      <g id="glyph-3-11">        <path d="m 6.59375,0.203125 c -0.824219,0 -1.578125,-0.20703125 -2.265625,-0.625 -0.6875,-0.414063 -1.226563,-0.945313 -1.609375,-1.59375 V 0 H 1.5 v -14.59375 h 1.359375 v 6.359375 c 0.46875,-0.707031 1.023437,-1.273437 1.671875,-1.703125 0.65625,-0.4375 1.414062,-0.65625 2.28125,-0.65625 0.738281,0 1.394531,0.152344 1.96875,0.453125 0.582031,0.304687 1.078125,0.714844 1.484375,1.234375 0.40625,0.511719 0.71875,1.089844 0.9375,1.734375 0.226563,0.648437 0.34375,1.308594 0.34375,1.984375 0,0.742188 -0.132813,1.4375 -0.390625,2.09375 -0.25,0.648438 -0.601562,1.214844 -1.046875,1.703125 -0.4375,0.492187 -0.960937,0.882813 -1.5625,1.171875 C 7.941406,0.0625 7.289062,0.203125 6.59375,0.203125 Z M 6.28125,-1 C 6.851562,-1 7.375,-1.113281 7.84375,-1.34375 8.320312,-1.582031 8.726562,-1.894531 9.0625,-2.28125 9.40625,-2.675781 9.671875,-3.125 9.859375,-3.625 c 0.195313,-0.5 0.296875,-1.019531 0.296875,-1.5625 0,-0.539062 -0.08984,-1.0625 -0.265625,-1.5625 C 9.710938,-7.257812 9.457031,-7.710938 9.125,-8.109375 8.800781,-8.503906 8.410156,-8.816406 7.953125,-9.046875 7.492188,-9.285156 6.988281,-9.40625 6.4375,-9.40625 c -0.417969,0 -0.808594,0.074219 -1.171875,0.21875 -0.355469,0.136719 -0.6875,0.324219 -1,0.5625 -0.304687,0.242188 -0.574219,0.523438 -0.8125,0.84375 -0.242187,0.3125 -0.4375,0.640625 -0.59375,0.984375 V -3.5625 c 0.050781,0.367188 0.191406,0.703125 0.421875,1.015625 0.238281,0.3125 0.519531,0.585937 0.84375,0.8125 0.332031,0.21875 0.6875,0.398437 1.0625,0.53125 C 5.570312,-1.066406 5.9375,-1 6.28125,-1 Z m 0,0" id="path81"></path>      </g>      <g id="glyph-3-12">        <path d="m 6.5,-0.5 c -0.085938,0.042969 -0.203125,0.101562 -0.359375,0.171875 -0.148437,0.0625 -0.324219,0.125 -0.53125,0.1875 C 5.410156,-0.078125 5.1875,-0.0195312 4.9375,0.03125 4.6875,0.09375 4.421875,0.125 4.140625,0.125 3.847656,0.125 3.566406,0.0820312 3.296875,0 3.035156,-0.0820312 2.800781,-0.207031 2.59375,-0.375 2.394531,-0.539062 2.234375,-0.75 2.109375,-1 1.992188,-1.25 1.9375,-1.539062 1.9375,-1.875 V -9.34375 H 0.5 v -1.078125 H 1.9375 V -13.9375 h 1.359375 v 3.515625 h 2.40625 v 1.078125 h -2.40625 v 7.078125 c 0.03125,0.386719 0.164063,0.671875 0.40625,0.859375 0.25,0.179688 0.535156,0.265625 0.859375,0.265625 0.382812,0 0.722656,-0.0625 1.015625,-0.1875 0.289063,-0.125 0.476563,-0.210937 0.5625,-0.265625 z m 0,0" id="path84"></path>      </g>      <g id="glyph-3-13">        <path d="M 10.265625,0 H 8.90625 v -5.8125 c 0,-1.25 -0.1875,-2.148438 -0.5625,-2.703125 -0.367188,-0.5625 -0.929688,-0.84375 -1.6875,-0.84375 -0.398438,0 -0.796875,0.074219 -1.203125,0.21875 C 5.054688,-8.992188 4.6875,-8.785156 4.34375,-8.515625 4.007812,-8.253906 3.707031,-7.9375 3.4375,-7.5625 3.175781,-7.195312 2.984375,-6.800781 2.859375,-6.375 V 0 H 1.5 V -10.421875 H 2.734375 V -8.0625 c 0.425781,-0.757812 1.035156,-1.367188 1.828125,-1.828125 0.800781,-0.46875 1.664062,-0.703125 2.59375,-0.703125 0.570312,0 1.054688,0.109375 1.453125,0.328125 0.40625,0.210937 0.726563,0.507813 0.96875,0.890625 0.238281,0.386719 0.410156,0.859375 0.515625,1.421875 0.113281,0.554687 0.171875,1.171875 0.171875,1.859375 z m 0,0" id="path87"></path>      </g>      <g id="glyph-3-14">        <path d="m 0.84375,-7.203125 c 0,-0.851563 0.144531,-1.703125 0.4375,-2.546875 0.300781,-0.84375 0.738281,-1.597656 1.3125,-2.265625 0.570312,-0.664063 1.28125,-1.207031 2.125,-1.625 0.84375,-0.425781 1.796875,-0.640625 2.859375,-0.640625 1.269531,0 2.351563,0.289062 3.25,0.859375 0.90625,0.574219 1.566406,1.320313 1.984375,2.234375 L 11.71875,-10.5 c -0.230469,-0.46875 -0.507812,-0.859375 -0.828125,-1.171875 -0.324219,-0.320313 -0.671875,-0.582031 -1.046875,-0.78125 -0.375,-0.207031 -0.765625,-0.351563 -1.171875,-0.4375 -0.398437,-0.08203 -0.789063,-0.125 -1.171875,-0.125 -0.855469,0 -1.609375,0.171875 -2.265625,0.515625 -0.648437,0.34375 -1.195313,0.796875 -1.640625,1.359375 -0.449219,0.5625 -0.789062,1.195313 -1.015625,1.890625 -0.230469,0.699219 -0.34375,1.40625 -0.34375,2.125 0,0.792969 0.132813,1.546875 0.40625,2.265625 0.28125,0.71875 0.65625,1.355469 1.125,1.90625 0.476563,0.554687 1.039063,0.996094 1.6875,1.328125 0.644531,0.324219 1.347656,0.484375 2.109375,0.484375 0.394531,0 0.800781,-0.046875 1.21875,-0.140625 C 9.207031,-1.382812 9.613281,-1.546875 10,-1.765625 c 0.394531,-0.21875 0.757812,-0.488281 1.09375,-0.8125 0.332031,-0.332031 0.613281,-0.734375 0.84375,-1.203125 l 1.15625,0.59375 c -0.21875,0.542969 -0.539062,1.015625 -0.953125,1.421875 -0.417969,0.40625 -0.882813,0.75 -1.390625,1.03125 -0.5,0.28125 -1.039062,0.496094 -1.609375,0.640625 -0.5625,0.1445312 -1.117187,0.21875 -1.65625,0.21875 -0.980469,0 -1.875,-0.2109375 -2.6875,-0.640625 C 3.984375,-0.941406 3.28125,-1.5 2.6875,-2.1875 2.101562,-2.875 1.648438,-3.65625 1.328125,-4.53125 1.003906,-5.40625 0.84375,-6.296875 0.84375,-7.203125 Z m 0,0" id="path90"></path>      </g>      <g id="glyph-3-15">        <path d="M 10.265625,0 H 8.90625 V -5.8125 C 8.90625,-7 8.703125,-7.882812 8.296875,-8.46875 7.890625,-9.0625 7.289062,-9.359375 6.5,-9.359375 c -0.386719,0 -0.773438,0.078125 -1.15625,0.234375 -0.375,0.148438 -0.730469,0.355469 -1.0625,0.625 -0.335938,0.273438 -0.625,0.589844 -0.875,0.953125 C 3.164062,-7.191406 2.984375,-6.800781 2.859375,-6.375 V 0 H 1.5 v -14.59375 h 1.359375 v 6.53125 c 0.414063,-0.78125 0.988281,-1.394531 1.71875,-1.84375 0.738281,-0.457031 1.53125,-0.6875 2.375,-0.6875 0.601563,0 1.109375,0.109375 1.515625,0.328125 0.414062,0.21875 0.757812,0.527344 1.03125,0.921875 0.269531,0.386719 0.460938,0.859375 0.578125,1.421875 0.125,0.554687 0.1875,1.164063 0.1875,1.828125 z m 0,0" id="path93"></path>      </g>      <g id="glyph-3-16">        <path d="m 4.765625,0.203125 c -1.136719,0 -1.980469,-0.375 -2.53125,-1.125 -0.554687,-0.757813 -0.828125,-1.882813 -0.828125,-3.375 v -6.125 h 1.359375 v 5.875 C 2.765625,-2.179688 3.570312,-1 5.1875,-1 c 0.394531,0 0.785156,-0.066406 1.171875,-0.203125 0.382813,-0.132813 0.738281,-0.328125 1.0625,-0.578125 0.332031,-0.25 0.628906,-0.546875 0.890625,-0.890625 0.257812,-0.351563 0.46875,-0.75 0.625,-1.1875 v -6.5625 h 1.359375 v 8.65625 c 0,0.375 0.160156,0.5625 0.484375,0.5625 V 0 c -0.15625,0.03125 -0.289062,0.046875 -0.390625,0.046875 -0.105469,0 -0.171875,0 -0.203125,0 C 9.863281,0.0234375 9.597656,-0.0859375 9.390625,-0.296875 9.191406,-0.515625 9.09375,-0.800781 9.09375,-1.15625 V -2.359375 C 8.644531,-1.546875 8.03125,-0.914062 7.25,-0.46875 6.46875,-0.0195312 5.640625,0.203125 4.765625,0.203125 Z m 0,0" id="path96"></path>      </g>      <g id="glyph-3-17">        <path d="m 0.78125,-5.234375 c 0,-0.738281 0.125,-1.429687 0.375,-2.078125 0.257812,-0.65625 0.617188,-1.226562 1.078125,-1.71875 0.457031,-0.488281 1.003906,-0.867188 1.640625,-1.140625 0.644531,-0.28125 1.351562,-0.421875 2.125,-0.421875 0.988281,0 1.847656,0.226562 2.578125,0.671875 0.738281,0.4375 1.289063,1.039063 1.65625,1.796875 l -1.3125,0.421875 C 8.628906,-8.234375 8.21875,-8.648438 7.6875,-8.953125 7.164062,-9.253906 6.582031,-9.40625 5.9375,-9.40625 c -0.53125,0 -1.027344,0.109375 -1.484375,0.328125 -0.460937,0.210937 -0.859375,0.5 -1.203125,0.875 -0.34375,0.367187 -0.617188,0.804687 -0.8125,1.3125 -0.1875,0.5 -0.28125,1.054687 -0.28125,1.65625 0,0.585937 0.097656,1.136719 0.296875,1.65625 0.207031,0.523437 0.484375,0.976563 0.828125,1.359375 0.34375,0.375 0.742188,0.671875 1.203125,0.890625 C 4.953125,-1.109375 5.441406,-1 5.953125,-1 6.285156,-1 6.613281,-1.046875 6.9375,-1.140625 7.269531,-1.234375 7.578125,-1.363281 7.859375,-1.53125 8.140625,-1.695312 8.378906,-1.882812 8.578125,-2.09375 8.773438,-2.3125 8.914062,-2.539062 9,-2.78125 L 10.34375,-2.375 C 10.195312,-2 9.984375,-1.65625 9.703125,-1.34375 c -0.28125,0.3125 -0.617187,0.585938 -1,0.8125 C 8.328125,-0.300781 7.910156,-0.125 7.453125,0 6.992188,0.132812 6.515625,0.203125 6.015625,0.203125 5.253906,0.203125 4.550781,0.0625 3.90625,-0.21875 3.269531,-0.507812 2.71875,-0.90625 2.25,-1.40625 1.789062,-1.90625 1.429688,-2.484375 1.171875,-3.140625 0.910156,-3.796875 0.78125,-4.492188 0.78125,-5.234375 Z m 0,0" id="path99"></path>      </g>      <g id="glyph-1-0">        <path d="M 4.410156,-8.792969 H 0.96875 V 0 h 1.433594 v -3.738281 h 2.007812 c 1.421875,0 2.535156,-1.082031 2.535156,-2.503907 0,-1.40625 -1.113281,-2.550781 -2.535156,-2.550781 z m -0.203125,3.8125 H 2.402344 v -2.507812 h 1.804687 c 0.761719,0 1.332031,0.5 1.332031,1.261719 0,0.777343 -0.570312,1.246093 -1.332031,1.246093 z m 0,0" id="path102"></path>      </g>      <g id="glyph-1-1">        <path d="M 2.402344,-1.332031 V -8.792969 H 0.96875 V 0 h 4.527344 v -1.332031 z m 0,0" id="path105"></path>      </g>      <g id="glyph-1-2">        <path d="M 6.300781,0 H 7.855469 L 4.675781,-8.792969 H 3.078125 L -0.101562,0 h 1.550781 l 0.632812,-1.859375 h 3.589844 z m -3.796875,-3.121094 1.378906,-4.101562 1.378907,4.101562 z m 0,0" id="path108"></path>      </g>      <g id="glyph-1-3">        <path d="m 7.28125,-8.792969 v 6.523438 L 2.402344,-8.792969 H 0.96875 V 0 H 2.402344 V -6.535156 L 7.269531,0 h 1.433594 v -8.792969 z m 0,0" id="path111"></path>      </g>      <g id="glyph-1-4">        <path d="M 6.109375,-8.792969 H 0.03125 v 1.335938 H 2.359375 V 0 H 3.78125 v -7.457031 h 2.328125 z m 0,0" id="path114"></path>      </g>      <g id="glyph-1-5">        <path d="m 1.039062,0 h 1.4375 v -8.792969 h -1.4375 z m 0,0" id="path117"></path>      </g>      <g id="glyph-1-6">        <path d="m 5.15625,-4.746094 v 1.199219 h 2.992188 c -0.351563,1.320313 -1.539063,2.242187 -2.976563,2.242187 -1.742187,0 -3.105469,-1.347656 -3.105469,-3.089843 0,-1.746094 1.363282,-3.09375 3.105469,-3.09375 1.1875,0 2.199219,0.628906 2.726563,1.597656 l 1.230468,-0.660156 c -0.761718,-1.40625 -2.257812,-2.359375 -3.957031,-2.359375 -2.503906,0 -4.511719,2.023437 -4.511719,4.515625 0,2.488281 2.007813,4.511719 4.511719,4.511719 2.492187,0 4.515625,-2.023438 4.515625,-4.511719 v -0.351563 z m 0,0" id="path120"></path>      </g>      <g id="glyph-1-7">        <path d="M 0.96875,0 H 3.605469 C 6.035156,0 8,-1.964844 8,-4.394531 8,-6.828125 6.035156,-8.792969 3.605469,-8.792969 H 0.96875 Z m 1.417969,-1.332031 v -6.125 h 1.21875 c 1.667969,0 2.972656,1.300781 2.972656,3.0625 0,1.757812 -1.304687,3.0625 -2.972656,3.0625 z m 0,0" id="path123"></path>      </g>      <g id="glyph-1-8">        <path d="M 10.578125,-8.792969 8.660156,-2.140625 6.769531,-8.792969 h -1.625 l -1.875,6.625 -1.90625,-6.625 H -0.160156 L 2.433594,0 H 4.03125 L 5.949219,-7.035156 7.898438,0 h 1.613281 l 2.5625,-8.792969 z m 0,0" id="path126"></path>      </g>      <g id="glyph-1-9">        <path d="M 6.082031,-7.488281 V -8.792969 H 1.039062 V 0 H 6.082031 V -1.304688 H 2.476562 V -3.738281 H 5.597656 V -5.054688 H 2.476562 v -2.433593 z m 0,0" id="path129"></path>      </g>      <g id="glyph-1-10">        <path d="m 5.039062,-3.824219 c 1.101563,-0.234375 1.90625,-1.203125 1.90625,-2.390625 0,-1.433594 -1.113281,-2.578125 -2.535156,-2.578125 H 0.96875 V 0 h 1.433594 v -3.691406 h 1.21875 L 5.949219,0 H 7.5625 Z M 2.402344,-4.9375 v -2.519531 h 1.832031 c 0.746094,0 1.304687,0.511719 1.304687,1.257812 0,0.75 -0.558593,1.261719 -1.304687,1.261719 z m 0,0" id="path132"></path>      </g>      <g id="glyph-1-11">        <path d="m 6.609375,-8.792969 v 3.722657 H 2.402344 V -8.792969 H 0.96875 V 0 H 2.402344 V -3.738281 H 6.609375 V 0 h 1.433594 v -8.792969 z m 0,0" id="path135"></path>      </g>      <g id="glyph-1-12">        <path d="m 5.144531,0.117188 c 2.488281,0 4.511719,-2.023438 4.511719,-4.511719 0,-2.492188 -2.023438,-4.515625 -4.511719,-4.515625 -2.492187,0 -4.515625,2.023437 -4.515625,4.515625 0,2.488281 2.023438,4.511719 4.515625,4.511719 z m 0,-1.421876 c -1.730469,0 -3.09375,-1.347656 -3.09375,-3.089843 0,-1.746094 1.363281,-3.09375 3.09375,-3.09375 1.742188,0 3.089844,1.347656 3.089844,3.09375 0,1.742187 -1.347656,3.089843 -3.089844,3.089843 z m 0,0" id="path138"></path>      </g>      <g id="glyph-1-13">        <path d="m 3.414062,0.117188 c 1.582032,0 2.871094,-0.9375 2.871094,-2.519532 0,-3.121094 -4.160156,-2.359375 -4.160156,-4.164062 0,-0.6875 0.542969,-1.007813 1.1875,-1.007813 0.585938,0 1.113281,0.261719 1.507812,0.761719 L 5.832031,-7.738281 C 5.273438,-8.425781 4.351562,-8.910156 3.3125,-8.910156 c -1.378906,0 -2.625,0.867187 -2.625,2.449218 0,2.914063 4.164062,2.195313 4.164062,4.058594 0,0.71875 -0.601562,1.140625 -1.421874,1.140625 -0.894532,0 -1.570313,-0.496093 -1.875,-1.242187 l -1.230469,0.746094 c 0.511719,1.097656 1.683593,1.875 3.089843,1.875 z m 0,0" id="path141"></path>      </g>      <g id="glyph-2-0"></g>    </g>    <clipPath id="clip-0">      <path d="M 95.105469,61.609375 H 279.60547 V 187.60937 H 95.105469 Z m 0,0" clip-rule="nonzero" id="path147"></path>    </clipPath>  </defs>  <rect x="-132.60547" width="450" fill="#ffffff" y="-99.226562" height="450" fill-opacity="1" id="rect158" style="fill:#000000;fill-opacity:0"></rect>  <g clip-path="url(#clip-0)" id="g166" style="fill:#ffffff;fill-opacity:1;stroke:none;stroke-opacity:1;opacity:1" transform="translate(-95.105469,-61.726562)">    <path fill="rgb(100%, 100%, 100%)" d="M 187.28516,61.726562 95.105469,135.17578 h 24.859371 l 67.36328,-54.433592 67.36719,54.433592 h 24.85547 z m 0.0352,28.3125 -60.57422,48.949218 c 6.01953,27.01953 29.11328,46.80859 56.67969,48.5625 -0.0195,-0.0117 -0.0195,-0.0234 -0.0195,-0.0391 0,-0.004 -0.0117,-0.0234 -0.0117,-0.0312 0,-0.0117 -0.0273,-0.0625 -0.0273,-0.0625 -0.4961,-1.22266 -5.97657,-15.48438 0.73828,-36.03516 -12.39453,1.47656 -20.08985,-3.30078 -24.55469,-8.60547 -4.70312,-5.5664 -5.96484,-11.70703 -5.96484,-11.70703 l -0.19141,-0.92578 0.93359,-0.082 c 1.82813,-0.16797 3.5586,-0.23047 5.19141,-0.21094 11.42969,0.14063 18.21094,4.53906 22.08594,9.23438 2.21875,2.68359 3.5,5.46484 4.22265,7.57031 0.51954,-1.27734 1.07813,-2.57422 1.69141,-3.89844 l -0.14062,-0.0195 -0.20313,-0.35156 c -2.66797,-4.60547 -3.07812,-9.11328 -2.15234,-13.1836 0.92187,-4.0664 3.13672,-7.69531 5.64843,-10.73437 5.02735,-6.07422 11.33204,-9.84375 11.33204,-9.84375 l 1.12109,-0.67187 0.14062,1.30078 c 1.92188,17.64843 -1.73437,26.125 -6.10546,30.14453 -3.53516,3.25 -7.42969,3.46093 -8.8125,3.42578 -0.65625,2.64453 -1.41797,6.41406 -1.8086,10.78125 0.38672,-0.71875 0.79688,-1.44531 1.25,-2.1836 4.25391,-6.88281 11.91016,-14.22656 25.10547,-15.88281 l 0.86719,-0.10156 0.0703,0.86719 c 0,0 0.48047,5.78125 -3.10156,12.3125 -3.34375,6.10547 -10.25,12.8789 -24.1211,16.42578 0.86719,7.69531 3.63282,15.6875 10.1875,21.84765 25.23438,-3.92968 45.4961,-22.9414 51.07813,-47.92187 z m 14.34375,20.867188 c -1.51172,0.97656 -5.6875,3.78125 -9.70312,8.63672 -2.40235,2.90234 -4.46485,6.32031 -5.30469,10.03125 -0.57031,2.5 -0.57031,5.13672 0.23437,7.89453 1.03516,-4.09375 4.65235,-15.38281 14.87891,-25.29687 -0.0352,-0.41797 -0.0664,-0.83594 -0.10547,-1.26563 z m 0.1211,1.49609 c -2.57422,3.32422 -10.21094,14.28125 -11.34375,28.60938 1.46875,-0.24219 3.51562,-0.9375 5.58593,-2.84375 3.61328,-3.32031 6.91407,-10.58594 5.75782,-25.76563 z m -42.61329,19.21875 c -1.15234,-0.004 -2.35546,0.0352 -3.61718,0.11719 0.32422,1.30469 1.49609,5.49219 5.26953,9.96094 3.96094,4.69531 10.5,8.86719 21.25,8.09765 -1.28516,-2.52343 -6.13672,-9.77343 -21.05469,-14.69921 0,0 0.22266,-0.0117 0.625,-0.0117 2.60156,0.0117 12.78906,0.55078 20.58594,7.74218 -0.52735,-0.85937 -1.15625,-1.75781 -1.91016,-2.67578 -3.625,-4.38672 -9.98828,-8.48437 -21.14844,-8.53125 z m 52.9375,5.83203 c -12,1.83594 -18.95312,8.46485 -22.89843,14.84766 -1.29297,2.09375 -2.23047,4.01953 -2.90625,5.69141 0,0.32031 -0.0156,0.63672 -0.0156,0.95703 2.83985,-4.03906 8.27344,-9.30078 18.61328,-13.42969 0,0 -13.53125,7.10938 -17.17187,17.36719 12.37109,-3.51953 18.53906,-9.60938 21.53906,-15.07422 2.85156,-5.20703 2.87109,-9.11328 2.83984,-10.35938 z m 0,0" fill-opacity="1" fill-rule="nonzero" id="path164" style="fill:#ffffff;fill-opacity:1;stroke:none;stroke-opacity:1"></path>  </g>  <g fill="#ffffff" fill-opacity="1" id="g182" transform="translate(-95.105469,-61.726562)">    <use x="78.275932" y="233.74185" xlink:href="#glyph-0-4" xlink:type="simple" xlink:actuate="onLoad" xlink:show="embed" id="use180" width="100%" height="100%"></use>  </g>  <g fill="#ffffff" fill-opacity="1" id="g222" transform="translate(-95.105469,-61.726562)">    <use x="224.06978" y="233.74185" xlink:href="#glyph-0-4" xlink:type="simple" xlink:actuate="onLoad" xlink:show="embed" id="use220" width="100%" height="100%"></use>  </g>  <g fill="#ffffff" fill-opacity="1" id="g258" transform="translate(-95.105469,-61.726562)">    <use x="351.16122" y="233.74185" xlink:href="#glyph-0-4" xlink:type="simple" xlink:actuate="onLoad" xlink:show="embed" id="use256" width="100%" height="100%"></use>  </g>  <g fill="#ffffff" fill-opacity="1" id="g294" transform="translate(-95.105469,-61.726562)">    <use x="210.25388" y="266.93246" xlink:href="#glyph-2-0" xlink:type="simple" xlink:actuate="onLoad" xlink:show="embed" id="use292" width="100%" height="100%"></use>  </g>  <g fill="#ffffff" fill-opacity="1" id="g342" transform="translate(-95.105469,-61.726562)">    <use x="178.40524" y="282.68246" xlink:href="#glyph-2-0" xlink:type="simple" xlink:actuate="onLoad" xlink:show="embed" id="use340" width="100%" height="100%"></use>  </g>  <g fill="#ffffff" fill-opacity="1" id="g358" transform="translate(-95.105469,-61.726562)">    <use x="216.17934" y="282.68246" xlink:href="#glyph-2-0" xlink:type="simple" xlink:actuate="onLoad" xlink:show="embed" id="use356" width="100%" height="100%"></use>  </g>  <g fill="#ffffff" fill-opacity="1" id="g419" transform="translate(-95.105469,-61.726562)">    <use x="130.08908" y="43.699993" xlink:href="#glyph-3-8" xlink:type="simple" xlink:actuate="onLoad" xlink:show="embed" id="use417" width="100%" height="100%"></use>  </g>  <g fill="#ffffff" fill-opacity="1" id="g471" transform="translate(-95.105469,-61.726562)">    <use x="251.64113" y="43.699993" xlink:href="#glyph-3-8" xlink:type="simple" xlink:actuate="onLoad" xlink:show="embed" id="use469" width="100%" height="100%"></use>  </g></svg></p><p style="font-weight: 700; letter-spacing: -0.4736px; line-height: 1.2em; font-family: Raleway, sans-serif; font-size: 1.6667em; text-align: center;"><br></p><p style="font-weight: 700; letter-spacing: -0.4736px; line-height: 1.2em; font-family: Raleway, sans-serif; font-size: 1.6667em; text-align: center; color: rgb(234, 236, 237);">NEW SANCTUARY CAMPAIGN<br></p><p style="letter-spacing: -0.4736px; line-height: 1.8em; font-family: &quot;Proxima Nova&quot;, &quot;Proxima Nova Heavy&quot;, sans-serif; font-weight: 400; font-size: 1.1765em; color: rgb(234, 236, 237);">PLANTING AND WATERING THE GOSPEL</p><p style="font-size: 23.68px; letter-spacing: -0.4736px; line-height: 1.2em; color: rgb(234, 236, 237);"><br></p><p style="font-weight: 700; letter-spacing: -0.4736px; line-height: 1.2em; font-family: &quot;Lobster Two&quot;, cursive; font-size: 1.1765em; color: rgb(234, 236, 237);"><span class="clovercustom" style="font-weight: 400;"><span class="clovercustom" style="letter-spacing: normal; font-weight: 400;">"I planted, Apollos watered, but God gave the growth." 1 Cor 3:6</span></span></p></div></div>`,
  entry: { ...entry, selector: '[data-id="19633956"]' },
  output: {
    data: [
      {
        type: "Cloneable",
        value: {
          _id: "1",
          _styles: ["wrapper-clone", "wrapper-clone--icon"],
          horizontalAlign: undefined,
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                code: '<svg xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:cc="http://creativecommons.org/ns#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" contentscripttype="text/ecmascript" width="184.44531" zoomAndPan="magnify" contentstyletype="text/css" viewBox="0 0 184.44531 125.82422" height="125.82422" preserveAspectRatio="xMidYMid meet" version="1.0" id="svg497" sodipodi:docname="Campaign Logo White SVG.svg" inkscape:version="1.0.2 (e86c870879, 2021-01-15)" style="">  <metadata id="metadata501">    <rdf:rdf>      <cc:work rdf:about="">        <dc:format>image/svg+xml</dc:format>        <dc:type rdf:resource="http://purl.org/dc/dcmitype/StillImage"></dc:type>        <dc:title></dc:title>      </cc:work>    </rdf:rdf>  </metadata>  <sodipodi:namedview pagecolor="#ffffff" bordercolor="#666666" borderopacity="1" objecttolerance="10" gridtolerance="10" guidetolerance="10" inkscape:pageopacity="0" inkscape:pageshadow="2" inkscape:window-width="1920" inkscape:window-height="1016" id="namedview499" showgrid="false" fit-margin-top="0" fit-margin-left="0" fit-margin-right="0" fit-margin-bottom="0" inkscape:zoom="2.0822542" inkscape:cx="94.868605" inkscape:cy="106.37725" inkscape:window-x="0" inkscape:window-y="27" inkscape:window-maximized="1" inkscape:current-layer="svg497" inkscape:document-rotation="0"></sodipodi:namedview>  <defs id="defs156">    <g id="g145">      <g id="glyph-0-0"></g>      <g id="glyph-0-1">        <path d="M 3.65625,-13.34375 V 0 H 2.046875 V -16.328125 H 3.28125 l 10.8125,13.59375 V -16.3125 h 1.609375 V 0 H 14.3125 Z m 0,0" id="path9"></path>      </g>      <g id="glyph-0-2">        <path d="M 13.015625,-1.421875 V 0 H 2.046875 V -16.328125 H 12.8125 v 1.421875 H 3.65625 v 5.890625 h 7.984375 V -7.65625 H 3.65625 v 6.234375 z m 0,0" id="path12"></path>      </g>      <g id="glyph-0-3">        <path d="M 8.1875,-16.21875 H 9.703125 L 12,-10.421875 14.328125,-16.21875 H 15.875 l -2.859375,6.96875 3.125,7.390625 5.921875,-14.46875 h 1.75 L 16.875,0 H 15.453125 L 12.03125,-8.078125 8.578125,0 H 7.171875 L 0.28125,-16.328125 H 2 L 7.9375,-1.859375 11.046875,-9.25 Z m 0,0" id="path15"></path>      </g>      <g id="glyph-0-4"></g>      <g id="glyph-0-5">        <path d="m 11.59375,-13.265625 c -0.460938,-0.519531 -1.074219,-0.941406 -1.84375,-1.265625 -0.761719,-0.320312 -1.636719,-0.484375 -2.625,-0.484375 -1.4375,0 -2.492188,0.273437 -3.15625,0.8125 -0.65625,0.542969 -0.984375,1.28125 -0.984375,2.21875 0,0.492187 0.085937,0.898437 0.265625,1.21875 0.175781,0.3125 0.445312,0.585937 0.8125,0.8125 0.375,0.230469 0.847656,0.429687 1.421875,0.59375 0.570313,0.167969 1.25,0.335937 2.03125,0.5 0.875,0.1875 1.660156,0.398437 2.359375,0.625 0.695312,0.230469 1.289062,0.515625 1.78125,0.859375 0.488281,0.34375 0.863281,0.765625 1.125,1.265625 0.257812,0.492187 0.390625,1.101563 0.390625,1.828125 0,0.75 -0.148437,1.402344 -0.4375,1.953125 -0.28125,0.554687 -0.6875,1.015625 -1.21875,1.390625 C 10.992188,-0.570312 10.375,-0.300781 9.65625,-0.125 8.9375,0.0507812 8.148438,0.140625 7.296875,0.140625 c -2.53125,0 -4.71875,-0.789063 -6.5625,-2.375 l 0.8125,-1.3125 C 1.835938,-3.234375 2.1875,-2.9375 2.59375,-2.65625 3,-2.382812 3.445312,-2.144531 3.9375,-1.9375 c 0.5,0.199219 1.03125,0.359375 1.59375,0.484375 0.570312,0.117187 1.175781,0.171875 1.8125,0.171875 1.300781,0 2.316406,-0.234375 3.046875,-0.703125 0.726563,-0.46875 1.09375,-1.175781 1.09375,-2.125 0,-0.507813 -0.105469,-0.9375 -0.3125,-1.28125 -0.210937,-0.34375 -0.523437,-0.640625 -0.9375,-0.890625 C 9.816406,-6.539062 9.300781,-6.757812 8.6875,-6.9375 8.082031,-7.125 7.375,-7.3125 6.5625,-7.5 5.695312,-7.695312 4.941406,-7.898438 4.296875,-8.109375 3.648438,-8.328125 3.101562,-8.597656 2.65625,-8.921875 c -0.4375,-0.320313 -0.773438,-0.707031 -1,-1.15625 C 1.4375,-10.535156 1.328125,-11.09375 1.328125,-11.75 c 0,-0.75 0.140625,-1.421875 0.421875,-2.015625 0.289062,-0.59375 0.695312,-1.082031 1.21875,-1.46875 0.519531,-0.394531 1.128906,-0.691406 1.828125,-0.890625 0.707031,-0.207031 1.492187,-0.3125 2.359375,-0.3125 1.082031,0 2.050781,0.167969 2.90625,0.5 0.851562,0.324219 1.625,0.78125 2.3125,1.375 z m 0,0" id="path19"></path>      </g>      <g id="glyph-0-6">        <path d="M 7.109375,-16.328125 H 8.46875 L 15.25,0 H 13.546875 L 11.4375,-5.109375 H 4.09375 L 2,0 H 0.28125 Z M 11.0625,-6.375 7.78125,-14.4375 4.4375,-6.375 Z m 0,0" id="path22"></path>      </g>      <g id="glyph-0-7">        <path d="m 0.96875,-8.28125 c 0,-0.976562 0.171875,-1.953125 0.515625,-2.921875 0.34375,-0.976563 0.84375,-1.847656 1.5,-2.609375 0.664063,-0.769531 1.476563,-1.394531 2.4375,-1.875 0.96875,-0.488281 2.066406,-0.734375 3.296875,-0.734375 1.457031,0 2.703125,0.335937 3.734375,1 1.03125,0.65625 1.796875,1.511719 2.296875,2.5625 l -1.265625,0.78125 c -0.261719,-0.53125 -0.585937,-0.976563 -0.96875,-1.34375 -0.375,-0.375 -0.777344,-0.675781 -1.203125,-0.90625 -0.429688,-0.226563 -0.871094,-0.390625 -1.328125,-0.484375 -0.460937,-0.101562 -0.914063,-0.15625 -1.359375,-0.15625 -0.980469,0 -1.84375,0.199219 -2.59375,0.59375 -0.75,0.398438 -1.386719,0.917969 -1.90625,1.5625 -0.511719,0.648438 -0.898438,1.371094 -1.15625,2.171875 -0.261719,0.804687 -0.390625,1.621094 -0.390625,2.453125 0,0.90625 0.15625,1.777344 0.46875,2.609375 0.3125,0.824219 0.738281,1.554687 1.28125,2.1875 0.550781,0.636719 1.195313,1.140625 1.9375,1.515625 0.75,0.375 1.554687,0.5625 2.421875,0.5625 0.46875,0 0.941406,-0.054688 1.421875,-0.171875 0.476563,-0.113281 0.941406,-0.296875 1.390625,-0.546875 0.457031,-0.257812 0.878906,-0.578125 1.265625,-0.953125 0.382813,-0.375 0.707031,-0.828125 0.96875,-1.359375 l 1.328125,0.6875 c -0.261719,0.617188 -0.632812,1.15625 -1.109375,1.625 -0.46875,0.46875 -0.996094,0.867188 -1.578125,1.1875 -0.585938,0.3125 -1.203125,0.558594 -1.859375,0.734375 -0.648437,0.1640625 -1.28125,0.25 -1.90625,0.25 -1.125,0 -2.15625,-0.242187 -3.09375,-0.734375 -0.9375,-0.488281 -1.746094,-1.128906 -2.421875,-1.921875 -0.667969,-0.789063 -1.1875,-1.6875 -1.5625,-2.6875 -0.375,-1.007813 -0.5625,-2.035156 -0.5625,-3.078125 z m 0,0" id="path25"></path>      </g>      <g id="glyph-0-8">        <path d="M 13.640625,-14.90625 H 7.8125 V 0 H 6.203125 v -14.90625 h -5.8125 v -1.421875 h 13.25 z m 0,0" id="path28"></path>      </g>      <g id="glyph-0-9">        <path d="m 8.671875,-1.328125 c 1.03125,0 1.882813,-0.191406 2.5625,-0.578125 0.6875,-0.394531 1.234375,-0.910156 1.640625,-1.546875 0.40625,-0.644531 0.6875,-1.375 0.84375,-2.1875 0.164062,-0.8125 0.25,-1.628906 0.25,-2.453125 v -8.234375 h 1.609375 v 8.234375 c 0,1.105469 -0.125,2.152344 -0.375,3.140625 -0.25,0.992187 -0.648437,1.859375 -1.1875,2.609375 -0.542969,0.75 -1.25,1.351562 -2.125,1.796875 -0.875,0.4375 -1.945313,0.65625 -3.203125,0.65625 -1.28125,0 -2.367188,-0.226563 -3.25,-0.6875 C 4.550781,-1.046875 3.835938,-1.664062 3.296875,-2.4375 2.765625,-3.207031 2.382812,-4.082031 2.15625,-5.0625 1.925781,-6.039062 1.8125,-7.050781 1.8125,-8.09375 v -8.234375 h 1.59375 v 8.234375 c 0,0.855469 0.082031,1.6875 0.25,2.5 0.164062,0.8125 0.453125,1.539062 0.859375,2.171875 0.40625,0.625 0.945313,1.132813 1.625,1.515625 0.675781,0.386719 1.519531,0.578125 2.53125,0.578125 z m 0,0" id="path31"></path>      </g>      <g id="glyph-0-10">        <path d="m 2.046875,0 v -16.328125 h 6.90625 c 0.695313,0 1.34375,0.152344 1.9375,0.453125 0.59375,0.292969 1.101563,0.683594 1.53125,1.171875 0.425781,0.480469 0.757813,1.027344 1,1.640625 0.238281,0.617188 0.359375,1.234375 0.359375,1.859375 0,0.605469 -0.08984,1.179687 -0.265625,1.71875 -0.179687,0.542969 -0.421875,1.03125 -0.734375,1.46875 -0.3125,0.4375 -0.695312,0.808594 -1.140625,1.109375 -0.449219,0.292969 -0.949219,0.496094 -1.5,0.609375 l 4,6.296875 h -1.8125 L 8.484375,-6.03125 H 3.65625 V 0 Z M 3.65625,-7.453125 H 9 c 0.46875,0 0.894531,-0.101563 1.28125,-0.3125 0.394531,-0.207031 0.726562,-0.484375 1,-0.828125 0.28125,-0.351562 0.5,-0.753906 0.65625,-1.203125 0.15625,-0.445313 0.234375,-0.914063 0.234375,-1.40625 0,-0.488281 -0.08984,-0.957031 -0.265625,-1.40625 -0.179688,-0.457031 -0.417969,-0.851563 -0.71875,-1.1875 -0.304688,-0.34375 -0.65625,-0.613281 -1.0625,-0.8125 C 9.71875,-14.804688 9.296875,-14.90625 8.859375,-14.90625 H 3.65625 Z m 0,0" id="path34"></path>      </g>      <g id="glyph-0-11">        <path d="m 2.140625,-16.328125 5.328125,8.78125 5.390625,-8.78125 h 1.75 l -6.328125,10.25 V 0 H 6.671875 V -6.125 L 0.375,-16.328125 Z m 0,0" id="path37"></path>      </g>      <g id="glyph-0-12">        <path d="M 16.515625,0 V -13.40625 L 10.5625,-3.03125 H 9.609375 L 3.65625,-13.40625 V 0 H 2.046875 v -16.328125 h 1.65625 l 6.375,11.171875 6.40625,-11.171875 H 18.125 V 0 Z m 0,0" id="path40"></path>      </g>      <g id="glyph-0-13">        <path d="M 2.046875,0 V -16.328125 H 8.8125 c 0.695312,0 1.34375,0.152344 1.9375,0.453125 0.59375,0.292969 1.101562,0.683594 1.53125,1.171875 0.425781,0.480469 0.757812,1.027344 1,1.640625 0.25,0.617188 0.375,1.234375 0.375,1.859375 0,0.679687 -0.117188,1.328125 -0.34375,1.953125 -0.230469,0.617188 -0.554688,1.164062 -0.96875,1.640625 -0.40625,0.46875 -0.902344,0.851563 -1.484375,1.140625 -0.574219,0.292969 -1.210937,0.4375 -1.90625,0.4375 H 3.65625 V 0 Z M 3.65625,-7.453125 H 8.875 c 0.476562,0 0.910156,-0.101563 1.296875,-0.3125 0.394531,-0.207031 0.726563,-0.484375 1,-0.828125 0.28125,-0.34375 0.492187,-0.738281 0.640625,-1.1875 0.15625,-0.457031 0.234375,-0.929688 0.234375,-1.421875 0,-0.5 -0.08984,-0.972656 -0.265625,-1.421875 -0.179688,-0.457031 -0.417969,-0.851562 -0.71875,-1.1875 -0.292969,-0.34375 -0.640625,-0.609375 -1.046875,-0.796875 -0.40625,-0.195313 -0.835937,-0.296875 -1.28125,-0.296875 H 3.65625 Z m 0,0" id="path43"></path>      </g>      <g id="glyph-0-14">        <path d="M 2.046875,0 V -16.328125 H 3.65625 V 0 Z m 0,0" id="path46"></path>      </g>      <g id="glyph-0-15">        <path d="m 13.84375,-2.390625 c -1.523438,1.667969 -3.265625,2.5 -5.234375,2.5 -1.105469,0 -2.125,-0.234375 -3.0625,-0.703125 C 4.617188,-1.070312 3.8125,-1.703125 3.125,-2.484375 2.445312,-3.265625 1.914062,-4.148438 1.53125,-5.140625 1.15625,-6.128906 0.96875,-7.144531 0.96875,-8.1875 c 0,-1.09375 0.1875,-2.132812 0.5625,-3.125 0.375,-1 0.894531,-1.878906 1.5625,-2.640625 0.675781,-0.757813 1.472656,-1.363281 2.390625,-1.8125 0.925781,-0.445313 1.9375,-0.671875 3.03125,-0.671875 0.789063,0 1.515625,0.08984 2.171875,0.265625 0.664062,0.167969 1.253906,0.414063 1.765625,0.734375 0.507813,0.3125 0.953125,0.6875 1.328125,1.125 0.382812,0.4375 0.707031,0.917969 0.96875,1.4375 l -1.25,0.828125 C 12.96875,-13.054688 12.269531,-13.800781 11.40625,-14.28125 10.550781,-14.757812 9.578125,-15 8.484375,-15 c -0.90625,0 -1.726563,0.195312 -2.453125,0.578125 -0.730469,0.386719 -1.351562,0.898437 -1.859375,1.53125 -0.511719,0.636719 -0.90625,1.367187 -1.1875,2.1875 -0.273437,0.824219 -0.40625,1.667969 -0.40625,2.53125 0,0.929687 0.15625,1.808594 0.46875,2.640625 0.3125,0.824219 0.742187,1.546875 1.296875,2.171875 0.550781,0.617187 1.195312,1.109375 1.9375,1.484375 0.75,0.367188 1.5625,0.546875 2.4375,0.546875 0.9375,0 1.828125,-0.21875 2.671875,-0.65625 0.851563,-0.4375 1.671875,-1.132813 2.453125,-2.09375 V -6.78125 h -3.625 v -1.25 h 4.984375 V 0 H 13.84375 Z m 0,0" id="path49"></path>      </g>      <g id="glyph-3-0"></g>      <g id="glyph-3-1">        <path d="m 7.125,-14.09375 h 1.3125 l 2,5.03125 2.015625,-5.03125 h 1.34375 L 11.3125,-8.046875 14.046875,-1.625 19.1875,-14.203125 h 1.515625 L 14.6875,0 h -1.25 L 10.453125,-7.015625 7.453125,0 h -1.21875 l -6,-14.203125 h 1.5 L 6.90625,-1.625 9.59375,-8.046875 Z m 0,0" id="path53"></path>      </g>      <g id="glyph-3-2">        <path d="m 4.125,0.203125 c -0.5,0 -0.960938,-0.085937 -1.375,-0.25 C 2.332031,-0.210938 1.96875,-0.441406 1.65625,-0.734375 1.34375,-1.035156 1.097656,-1.378906 0.921875,-1.765625 0.742188,-2.160156 0.65625,-2.585938 0.65625,-3.046875 c 0,-0.445313 0.101562,-0.863281 0.3125,-1.25 0.21875,-0.382813 0.519531,-0.71875 0.90625,-1 0.382812,-0.28125 0.84375,-0.5 1.375,-0.65625 0.539062,-0.15625 1.128906,-0.234375 1.765625,-0.234375 0.53125,0 1.066406,0.046875 1.609375,0.140625 0.550781,0.09375 1.039062,0.230469 1.46875,0.40625 v -0.9375 c 0,-0.914063 -0.261719,-1.644531 -0.78125,-2.1875 -0.511719,-0.539063 -1.230469,-0.8125 -2.15625,-0.8125 C 4.625,-9.578125 4.078125,-9.46875 3.515625,-9.25 2.960938,-9.039062 2.394531,-8.738281 1.8125,-8.34375 L 1.34375,-9.234375 c 1.34375,-0.90625 2.648438,-1.359375 3.921875,-1.359375 1.300781,0 2.320313,0.367188 3.0625,1.09375 0.75,0.730469 1.125,1.746094 1.125,3.046875 v 4.6875 c 0,0.375 0.164063,0.5625 0.5,0.5625 V 0 C 9.734375,0.0390625 9.5625,0.0625 9.4375,0.0625 9.09375,0.0625 8.820312,-0.0195312 8.625,-0.1875 8.4375,-0.363281 8.332031,-0.609375 8.3125,-0.921875 l -0.03125,-0.8125 c -0.480469,0.625 -1.085938,1.105469 -1.8125,1.4375 -0.730469,0.3320312 -1.511719,0.5 -2.34375,0.5 z M 4.4375,-0.84375 c 0.707031,0 1.359375,-0.128906 1.953125,-0.390625 0.59375,-0.269531 1.039063,-0.625 1.34375,-1.0625 C 7.859375,-2.421875 7.945312,-2.550781 8,-2.6875 8.0625,-2.832031 8.09375,-2.960938 8.09375,-3.078125 V -4.78125 C 7.644531,-4.957031 7.175781,-5.085938 6.6875,-5.171875 6.195312,-5.265625 5.703125,-5.3125 5.203125,-5.3125 c -0.960937,0 -1.742187,0.199219 -2.34375,0.59375 -0.605469,0.398438 -0.90625,0.921875 -0.90625,1.578125 0,0.324219 0.0625,0.625 0.1875,0.90625 0.132813,0.28125 0.3125,0.527344 0.53125,0.734375 0.21875,0.199219 0.476563,0.359375 0.78125,0.484375 0.3125,0.117187 0.640625,0.171875 0.984375,0.171875 z m 0,0" id="path56"></path>      </g>      <g id="glyph-3-3">        <path d="m 2.09375,3.203125 c 0.113281,0.00781 0.21875,0.015625 0.3125,0.015625 0.101562,0.00781 0.203125,0.015625 0.296875,0.015625 0.238281,0 0.425781,-0.03125 0.5625,-0.09375 0.144531,-0.0625 0.28125,-0.210937 0.40625,-0.4375 C 3.804688,2.472656 3.957031,2.144531 4.125,1.71875 4.289062,1.289062 4.53125,0.71875 4.84375,0 L 0.296875,-10.421875 h 1.40625 l 3.859375,9.15625 3.578125,-9.15625 H 10.4375 l -5.390625,13.4375 c -0.15625,0.382813 -0.40625,0.71875 -0.75,1 -0.335937,0.289063 -0.792969,0.4375 -1.375,0.4375 -0.136719,0 -0.265625,-0.00781 -0.390625,-0.015625 -0.117188,0 -0.261719,-0.011719 -0.4375,-0.03125 z m 0,0" id="path59"></path>      </g>      <g id="glyph-3-4">        <path d="m 4.953125,0.203125 c -0.8125,0 -1.605469,-0.1328125 -2.375,-0.390625 -0.773437,-0.269531 -1.4375,-0.660156 -2,-1.171875 l 0.625,-0.921875 c 0.582031,0.480469 1.175781,0.839844 1.78125,1.078125 C 3.597656,-0.960938 4.25,-0.84375 4.9375,-0.84375 c 0.84375,0 1.515625,-0.164062 2.015625,-0.5 0.5,-0.34375 0.75,-0.828125 0.75,-1.453125 0,-0.289063 -0.070313,-0.53125 -0.203125,-0.71875 C 7.363281,-3.710938 7.164062,-3.882812 6.90625,-4.03125 6.644531,-4.1875 6.316406,-4.316406 5.921875,-4.421875 5.535156,-4.535156 5.082031,-4.65625 4.5625,-4.78125 3.957031,-4.925781 3.4375,-5.070312 3,-5.21875 2.5625,-5.363281 2.195312,-5.53125 1.90625,-5.71875 1.625,-5.90625 1.410156,-6.132812 1.265625,-6.40625 1.128906,-6.675781 1.0625,-7.019531 1.0625,-7.4375 c 0,-0.519531 0.101562,-0.976562 0.3125,-1.375 0.207031,-0.40625 0.484375,-0.738281 0.828125,-1 0.351563,-0.257812 0.765625,-0.453125 1.234375,-0.578125 0.476562,-0.132813 0.984375,-0.203125 1.515625,-0.203125 0.800781,0 1.53125,0.132812 2.1875,0.390625 0.65625,0.261719 1.179687,0.601563 1.578125,1.015625 L 8.0625,-8.375 C 7.675781,-8.78125 7.203125,-9.082031 6.640625,-9.28125 c -0.554687,-0.195312 -1.125,-0.296875 -1.71875,-0.296875 -0.367187,0 -0.703125,0.039063 -1.015625,0.109375 C 3.601562,-9.394531 3.335938,-9.273438 3.109375,-9.109375 2.878906,-8.953125 2.695312,-8.75 2.5625,-8.5 c -0.136719,0.242188 -0.203125,0.527344 -0.203125,0.859375 0,0.28125 0.046875,0.511719 0.140625,0.6875 0.09375,0.167969 0.242188,0.320313 0.453125,0.453125 0.21875,0.125 0.488281,0.242188 0.8125,0.34375 0.332031,0.09375 0.726563,0.195312 1.1875,0.296875 0.664063,0.15625 1.253906,0.320313 1.765625,0.484375 0.507812,0.15625 0.929688,0.34375 1.265625,0.5625 0.34375,0.210938 0.597656,0.46875 0.765625,0.78125 0.175781,0.304688 0.265625,0.671875 0.265625,1.109375 0,0.960937 -0.371094,1.71875 -1.109375,2.28125 -0.730469,0.5625 -1.714844,0.84375 -2.953125,0.84375 z m 0,0" id="path62"></path>      </g>      <g id="glyph-3-5">        <path d="M 1.5,0 V -10.421875 H 2.859375 V 0 Z m 0,-12.59375 v -2 h 1.359375 v 2 z m 0,0" id="path65"></path>      </g>      <g id="glyph-3-6">        <path d="m 5.78125,0.203125 c -0.71875,0 -1.386719,-0.1523438 -2,-0.453125 -0.617188,-0.300781 -1.140625,-0.695312 -1.578125,-1.1875 -0.4375,-0.5 -0.78125,-1.070312 -1.03125,-1.71875 -0.25,-0.644531 -0.375,-1.320312 -0.375,-2.03125 0,-0.726562 0.117187,-1.421875 0.359375,-2.078125 C 1.394531,-7.929688 1.722656,-8.507812 2.140625,-9 c 0.425781,-0.488281 0.925781,-0.875 1.5,-1.15625 0.582031,-0.289062 1.222656,-0.4375 1.921875,-0.4375 0.875,0 1.648438,0.230469 2.328125,0.6875 0.6875,0.460938 1.222656,1.015625 1.609375,1.671875 v -6.359375 h 1.359375 v 12.828125 c 0,0.375 0.160156,0.5625 0.484375,0.5625 V 0 c -0.199219,0.0390625 -0.359375,0.0625 -0.484375,0.0625 -0.324219,0 -0.605469,-0.1015625 -0.84375,-0.3125 C 9.773438,-0.457031 9.65625,-0.707031 9.65625,-1 V -2.015625 C 9.25,-1.335938 8.691406,-0.796875 7.984375,-0.390625 7.273438,0.00390625 6.539062,0.203125 5.78125,0.203125 Z M 6.078125,-1 c 0.332031,0 0.6875,-0.066406 1.0625,-0.203125 C 7.523438,-1.335938 7.882812,-1.519531 8.21875,-1.75 8.550781,-1.976562 8.832031,-2.25 9.0625,-2.5625 9.300781,-2.882812 9.445312,-3.222656 9.5,-3.578125 v -3.21875 C 9.363281,-7.160156 9.171875,-7.5 8.921875,-7.8125 8.671875,-8.125 8.378906,-8.398438 8.046875,-8.640625 7.722656,-8.878906 7.375,-9.066406 7,-9.203125 6.625,-9.335938 6.257812,-9.40625 5.90625,-9.40625 c -0.5625,0 -1.074219,0.121094 -1.53125,0.359375 -0.449219,0.242187 -0.839844,0.558594 -1.171875,0.953125 -0.324219,0.398438 -0.574219,0.851562 -0.75,1.359375 -0.167969,0.5 -0.25,1.015625 -0.25,1.546875 0,0.5625 0.097656,1.101562 0.296875,1.609375 C 2.695312,-3.066406 2.96875,-2.625 3.3125,-2.25 3.664062,-1.875 4.078125,-1.570312 4.546875,-1.34375 5.023438,-1.113281 5.535156,-1 6.078125,-1 Z m 0,0" id="path68"></path>      </g>      <g id="glyph-3-7">        <path d="M 6,0.203125 C 5.238281,0.203125 4.539062,0.0625 3.90625,-0.21875 3.269531,-0.507812 2.71875,-0.90625 2.25,-1.40625 1.789062,-1.90625 1.429688,-2.484375 1.171875,-3.140625 0.910156,-3.796875 0.78125,-4.492188 0.78125,-5.234375 0.78125,-5.972656 0.910156,-6.664062 1.171875,-7.3125 1.429688,-7.957031 1.789062,-8.519531 2.25,-9 c 0.457031,-0.488281 1.003906,-0.875 1.640625,-1.15625 0.644531,-0.289062 1.34375,-0.4375 2.09375,-0.4375 0.757813,0 1.453125,0.148438 2.078125,0.4375 0.632812,0.28125 1.175781,0.667969 1.625,1.15625 0.457031,0.492188 0.8125,1.058594 1.0625,1.703125 0.257812,0.636719 0.390625,1.3125 0.390625,2.03125 0,0.117187 0,0.226563 0,0.328125 0,0.105469 -0.0078,0.179688 -0.01563,0.21875 h -8.92187 c 0.039063,0.5625 0.171875,1.085938 0.390625,1.5625 0.21875,0.46875 0.5,0.875 0.84375,1.21875 0.34375,0.34375 0.738281,0.617188 1.1875,0.8125 0.445312,0.1875 0.925781,0.28125 1.4375,0.28125 0.332031,0 0.664062,-0.046875 1,-0.140625 0.332031,-0.09375 0.640625,-0.21875 0.921875,-0.375 0.28125,-0.15625 0.53125,-0.347656 0.75,-0.578125 0.226563,-0.238281 0.40625,-0.5 0.53125,-0.78125 l 1.171875,0.3125 c -0.15625,0.398438 -0.386719,0.75 -0.6875,1.0625 -0.292969,0.3125 -0.632812,0.585938 -1.015625,0.8125 C 8.347656,-0.300781 7.921875,-0.125 7.453125,0 6.992188,0.132812 6.507812,0.203125 6,0.203125 Z m 3.84375,-5.96875 C 9.800781,-6.316406 9.671875,-6.820312 9.453125,-7.28125 9.234375,-7.75 8.953125,-8.148438 8.609375,-8.484375 8.265625,-8.816406 7.867188,-9.078125 7.421875,-9.265625 6.984375,-9.453125 6.507812,-9.546875 6,-9.546875 c -0.511719,0 -0.996094,0.09375 -1.453125,0.28125 -0.449219,0.1875 -0.84375,0.449219 -1.1875,0.78125 C 3.023438,-8.148438 2.753906,-7.75 2.546875,-7.28125 2.335938,-6.8125 2.21875,-6.304688 2.1875,-5.765625 Z m 0,0" id="path71"></path>      </g>      <g id="glyph-3-8"></g>      <g id="glyph-3-9">        <path d="m 1.78125,0 v -14.203125 h 5.875 c 0.613281,0 1.175781,0.132813 1.6875,0.390625 0.519531,0.261719 0.960938,0.605469 1.328125,1.03125 0.375,0.417969 0.664063,0.890625 0.875,1.421875 0.21875,0.53125 0.328125,1.074219 0.328125,1.625 0,0.585937 -0.101562,1.148437 -0.296875,1.6875 -0.199219,0.542969 -0.480469,1.023437 -0.84375,1.4375 -0.355469,0.40625 -0.78125,0.742187 -1.28125,1 -0.5,0.25 -1.058594,0.375 -1.671875,0.375 H 3.1875 V 0 Z M 3.1875,-6.484375 h 4.53125 c 0.414062,0 0.789062,-0.085937 1.125,-0.265625 0.34375,-0.175781 0.632812,-0.414062 0.875,-0.71875 0.238281,-0.300781 0.425781,-0.644531 0.5625,-1.03125 0.132812,-0.394531 0.203125,-0.804688 0.203125,-1.234375 0,-0.445313 -0.07813,-0.863281 -0.234375,-1.25 -0.15625,-0.394531 -0.367188,-0.738281 -0.625,-1.03125 -0.261719,-0.289063 -0.570312,-0.519531 -0.921875,-0.6875 -0.34375,-0.164063 -0.714844,-0.25 -1.109375,-0.25 H 3.1875 Z m 0,0" id="path75"></path>      </g>      <g id="glyph-3-10">        <path d="m 6.703125,-9.21875 c -0.929687,0.023438 -1.730469,0.277344 -2.40625,0.765625 -0.679687,0.480469 -1.15625,1.136719 -1.4375,1.96875 V 0 H 1.5 v -10.421875 h 1.28125 v 2.5 c 0.382812,-0.800781 0.898438,-1.425781 1.546875,-1.875 0.65625,-0.457031 1.359375,-0.6875 2.109375,-0.6875 0.101562,0 0.191406,0.01172 0.265625,0.03125 z m 0,0" id="path78"></path>      </g>      <g id="glyph-3-11">        <path d="m 6.59375,0.203125 c -0.824219,0 -1.578125,-0.20703125 -2.265625,-0.625 -0.6875,-0.414063 -1.226563,-0.945313 -1.609375,-1.59375 V 0 H 1.5 v -14.59375 h 1.359375 v 6.359375 c 0.46875,-0.707031 1.023437,-1.273437 1.671875,-1.703125 0.65625,-0.4375 1.414062,-0.65625 2.28125,-0.65625 0.738281,0 1.394531,0.152344 1.96875,0.453125 0.582031,0.304687 1.078125,0.714844 1.484375,1.234375 0.40625,0.511719 0.71875,1.089844 0.9375,1.734375 0.226563,0.648437 0.34375,1.308594 0.34375,1.984375 0,0.742188 -0.132813,1.4375 -0.390625,2.09375 -0.25,0.648438 -0.601562,1.214844 -1.046875,1.703125 -0.4375,0.492187 -0.960937,0.882813 -1.5625,1.171875 C 7.941406,0.0625 7.289062,0.203125 6.59375,0.203125 Z M 6.28125,-1 C 6.851562,-1 7.375,-1.113281 7.84375,-1.34375 8.320312,-1.582031 8.726562,-1.894531 9.0625,-2.28125 9.40625,-2.675781 9.671875,-3.125 9.859375,-3.625 c 0.195313,-0.5 0.296875,-1.019531 0.296875,-1.5625 0,-0.539062 -0.08984,-1.0625 -0.265625,-1.5625 C 9.710938,-7.257812 9.457031,-7.710938 9.125,-8.109375 8.800781,-8.503906 8.410156,-8.816406 7.953125,-9.046875 7.492188,-9.285156 6.988281,-9.40625 6.4375,-9.40625 c -0.417969,0 -0.808594,0.074219 -1.171875,0.21875 -0.355469,0.136719 -0.6875,0.324219 -1,0.5625 -0.304687,0.242188 -0.574219,0.523438 -0.8125,0.84375 -0.242187,0.3125 -0.4375,0.640625 -0.59375,0.984375 V -3.5625 c 0.050781,0.367188 0.191406,0.703125 0.421875,1.015625 0.238281,0.3125 0.519531,0.585937 0.84375,0.8125 0.332031,0.21875 0.6875,0.398437 1.0625,0.53125 C 5.570312,-1.066406 5.9375,-1 6.28125,-1 Z m 0,0" id="path81"></path>      </g>      <g id="glyph-3-12">        <path d="m 6.5,-0.5 c -0.085938,0.042969 -0.203125,0.101562 -0.359375,0.171875 -0.148437,0.0625 -0.324219,0.125 -0.53125,0.1875 C 5.410156,-0.078125 5.1875,-0.0195312 4.9375,0.03125 4.6875,0.09375 4.421875,0.125 4.140625,0.125 3.847656,0.125 3.566406,0.0820312 3.296875,0 3.035156,-0.0820312 2.800781,-0.207031 2.59375,-0.375 2.394531,-0.539062 2.234375,-0.75 2.109375,-1 1.992188,-1.25 1.9375,-1.539062 1.9375,-1.875 V -9.34375 H 0.5 v -1.078125 H 1.9375 V -13.9375 h 1.359375 v 3.515625 h 2.40625 v 1.078125 h -2.40625 v 7.078125 c 0.03125,0.386719 0.164063,0.671875 0.40625,0.859375 0.25,0.179688 0.535156,0.265625 0.859375,0.265625 0.382812,0 0.722656,-0.0625 1.015625,-0.1875 0.289063,-0.125 0.476563,-0.210937 0.5625,-0.265625 z m 0,0" id="path84"></path>      </g>      <g id="glyph-3-13">        <path d="M 10.265625,0 H 8.90625 v -5.8125 c 0,-1.25 -0.1875,-2.148438 -0.5625,-2.703125 -0.367188,-0.5625 -0.929688,-0.84375 -1.6875,-0.84375 -0.398438,0 -0.796875,0.074219 -1.203125,0.21875 C 5.054688,-8.992188 4.6875,-8.785156 4.34375,-8.515625 4.007812,-8.253906 3.707031,-7.9375 3.4375,-7.5625 3.175781,-7.195312 2.984375,-6.800781 2.859375,-6.375 V 0 H 1.5 V -10.421875 H 2.734375 V -8.0625 c 0.425781,-0.757812 1.035156,-1.367188 1.828125,-1.828125 0.800781,-0.46875 1.664062,-0.703125 2.59375,-0.703125 0.570312,0 1.054688,0.109375 1.453125,0.328125 0.40625,0.210937 0.726563,0.507813 0.96875,0.890625 0.238281,0.386719 0.410156,0.859375 0.515625,1.421875 0.113281,0.554687 0.171875,1.171875 0.171875,1.859375 z m 0,0" id="path87"></path>      </g>      <g id="glyph-3-14">        <path d="m 0.84375,-7.203125 c 0,-0.851563 0.144531,-1.703125 0.4375,-2.546875 0.300781,-0.84375 0.738281,-1.597656 1.3125,-2.265625 0.570312,-0.664063 1.28125,-1.207031 2.125,-1.625 0.84375,-0.425781 1.796875,-0.640625 2.859375,-0.640625 1.269531,0 2.351563,0.289062 3.25,0.859375 0.90625,0.574219 1.566406,1.320313 1.984375,2.234375 L 11.71875,-10.5 c -0.230469,-0.46875 -0.507812,-0.859375 -0.828125,-1.171875 -0.324219,-0.320313 -0.671875,-0.582031 -1.046875,-0.78125 -0.375,-0.207031 -0.765625,-0.351563 -1.171875,-0.4375 -0.398437,-0.08203 -0.789063,-0.125 -1.171875,-0.125 -0.855469,0 -1.609375,0.171875 -2.265625,0.515625 -0.648437,0.34375 -1.195313,0.796875 -1.640625,1.359375 -0.449219,0.5625 -0.789062,1.195313 -1.015625,1.890625 -0.230469,0.699219 -0.34375,1.40625 -0.34375,2.125 0,0.792969 0.132813,1.546875 0.40625,2.265625 0.28125,0.71875 0.65625,1.355469 1.125,1.90625 0.476563,0.554687 1.039063,0.996094 1.6875,1.328125 0.644531,0.324219 1.347656,0.484375 2.109375,0.484375 0.394531,0 0.800781,-0.046875 1.21875,-0.140625 C 9.207031,-1.382812 9.613281,-1.546875 10,-1.765625 c 0.394531,-0.21875 0.757812,-0.488281 1.09375,-0.8125 0.332031,-0.332031 0.613281,-0.734375 0.84375,-1.203125 l 1.15625,0.59375 c -0.21875,0.542969 -0.539062,1.015625 -0.953125,1.421875 -0.417969,0.40625 -0.882813,0.75 -1.390625,1.03125 -0.5,0.28125 -1.039062,0.496094 -1.609375,0.640625 -0.5625,0.1445312 -1.117187,0.21875 -1.65625,0.21875 -0.980469,0 -1.875,-0.2109375 -2.6875,-0.640625 C 3.984375,-0.941406 3.28125,-1.5 2.6875,-2.1875 2.101562,-2.875 1.648438,-3.65625 1.328125,-4.53125 1.003906,-5.40625 0.84375,-6.296875 0.84375,-7.203125 Z m 0,0" id="path90"></path>      </g>      <g id="glyph-3-15">        <path d="M 10.265625,0 H 8.90625 V -5.8125 C 8.90625,-7 8.703125,-7.882812 8.296875,-8.46875 7.890625,-9.0625 7.289062,-9.359375 6.5,-9.359375 c -0.386719,0 -0.773438,0.078125 -1.15625,0.234375 -0.375,0.148438 -0.730469,0.355469 -1.0625,0.625 -0.335938,0.273438 -0.625,0.589844 -0.875,0.953125 C 3.164062,-7.191406 2.984375,-6.800781 2.859375,-6.375 V 0 H 1.5 v -14.59375 h 1.359375 v 6.53125 c 0.414063,-0.78125 0.988281,-1.394531 1.71875,-1.84375 0.738281,-0.457031 1.53125,-0.6875 2.375,-0.6875 0.601563,0 1.109375,0.109375 1.515625,0.328125 0.414062,0.21875 0.757812,0.527344 1.03125,0.921875 0.269531,0.386719 0.460938,0.859375 0.578125,1.421875 0.125,0.554687 0.1875,1.164063 0.1875,1.828125 z m 0,0" id="path93"></path>      </g>      <g id="glyph-3-16">        <path d="m 4.765625,0.203125 c -1.136719,0 -1.980469,-0.375 -2.53125,-1.125 -0.554687,-0.757813 -0.828125,-1.882813 -0.828125,-3.375 v -6.125 h 1.359375 v 5.875 C 2.765625,-2.179688 3.570312,-1 5.1875,-1 c 0.394531,0 0.785156,-0.066406 1.171875,-0.203125 0.382813,-0.132813 0.738281,-0.328125 1.0625,-0.578125 0.332031,-0.25 0.628906,-0.546875 0.890625,-0.890625 0.257812,-0.351563 0.46875,-0.75 0.625,-1.1875 v -6.5625 h 1.359375 v 8.65625 c 0,0.375 0.160156,0.5625 0.484375,0.5625 V 0 c -0.15625,0.03125 -0.289062,0.046875 -0.390625,0.046875 -0.105469,0 -0.171875,0 -0.203125,0 C 9.863281,0.0234375 9.597656,-0.0859375 9.390625,-0.296875 9.191406,-0.515625 9.09375,-0.800781 9.09375,-1.15625 V -2.359375 C 8.644531,-1.546875 8.03125,-0.914062 7.25,-0.46875 6.46875,-0.0195312 5.640625,0.203125 4.765625,0.203125 Z m 0,0" id="path96"></path>      </g>      <g id="glyph-3-17">        <path d="m 0.78125,-5.234375 c 0,-0.738281 0.125,-1.429687 0.375,-2.078125 0.257812,-0.65625 0.617188,-1.226562 1.078125,-1.71875 0.457031,-0.488281 1.003906,-0.867188 1.640625,-1.140625 0.644531,-0.28125 1.351562,-0.421875 2.125,-0.421875 0.988281,0 1.847656,0.226562 2.578125,0.671875 0.738281,0.4375 1.289063,1.039063 1.65625,1.796875 l -1.3125,0.421875 C 8.628906,-8.234375 8.21875,-8.648438 7.6875,-8.953125 7.164062,-9.253906 6.582031,-9.40625 5.9375,-9.40625 c -0.53125,0 -1.027344,0.109375 -1.484375,0.328125 -0.460937,0.210937 -0.859375,0.5 -1.203125,0.875 -0.34375,0.367187 -0.617188,0.804687 -0.8125,1.3125 -0.1875,0.5 -0.28125,1.054687 -0.28125,1.65625 0,0.585937 0.097656,1.136719 0.296875,1.65625 0.207031,0.523437 0.484375,0.976563 0.828125,1.359375 0.34375,0.375 0.742188,0.671875 1.203125,0.890625 C 4.953125,-1.109375 5.441406,-1 5.953125,-1 6.285156,-1 6.613281,-1.046875 6.9375,-1.140625 7.269531,-1.234375 7.578125,-1.363281 7.859375,-1.53125 8.140625,-1.695312 8.378906,-1.882812 8.578125,-2.09375 8.773438,-2.3125 8.914062,-2.539062 9,-2.78125 L 10.34375,-2.375 C 10.195312,-2 9.984375,-1.65625 9.703125,-1.34375 c -0.28125,0.3125 -0.617187,0.585938 -1,0.8125 C 8.328125,-0.300781 7.910156,-0.125 7.453125,0 6.992188,0.132812 6.515625,0.203125 6.015625,0.203125 5.253906,0.203125 4.550781,0.0625 3.90625,-0.21875 3.269531,-0.507812 2.71875,-0.90625 2.25,-1.40625 1.789062,-1.90625 1.429688,-2.484375 1.171875,-3.140625 0.910156,-3.796875 0.78125,-4.492188 0.78125,-5.234375 Z m 0,0" id="path99"></path>      </g>      <g id="glyph-1-0">        <path d="M 4.410156,-8.792969 H 0.96875 V 0 h 1.433594 v -3.738281 h 2.007812 c 1.421875,0 2.535156,-1.082031 2.535156,-2.503907 0,-1.40625 -1.113281,-2.550781 -2.535156,-2.550781 z m -0.203125,3.8125 H 2.402344 v -2.507812 h 1.804687 c 0.761719,0 1.332031,0.5 1.332031,1.261719 0,0.777343 -0.570312,1.246093 -1.332031,1.246093 z m 0,0" id="path102"></path>      </g>      <g id="glyph-1-1">        <path d="M 2.402344,-1.332031 V -8.792969 H 0.96875 V 0 h 4.527344 v -1.332031 z m 0,0" id="path105"></path>      </g>      <g id="glyph-1-2">        <path d="M 6.300781,0 H 7.855469 L 4.675781,-8.792969 H 3.078125 L -0.101562,0 h 1.550781 l 0.632812,-1.859375 h 3.589844 z m -3.796875,-3.121094 1.378906,-4.101562 1.378907,4.101562 z m 0,0" id="path108"></path>      </g>      <g id="glyph-1-3">        <path d="m 7.28125,-8.792969 v 6.523438 L 2.402344,-8.792969 H 0.96875 V 0 H 2.402344 V -6.535156 L 7.269531,0 h 1.433594 v -8.792969 z m 0,0" id="path111"></path>      </g>      <g id="glyph-1-4">        <path d="M 6.109375,-8.792969 H 0.03125 v 1.335938 H 2.359375 V 0 H 3.78125 v -7.457031 h 2.328125 z m 0,0" id="path114"></path>      </g>      <g id="glyph-1-5">        <path d="m 1.039062,0 h 1.4375 v -8.792969 h -1.4375 z m 0,0" id="path117"></path>      </g>      <g id="glyph-1-6">        <path d="m 5.15625,-4.746094 v 1.199219 h 2.992188 c -0.351563,1.320313 -1.539063,2.242187 -2.976563,2.242187 -1.742187,0 -3.105469,-1.347656 -3.105469,-3.089843 0,-1.746094 1.363282,-3.09375 3.105469,-3.09375 1.1875,0 2.199219,0.628906 2.726563,1.597656 l 1.230468,-0.660156 c -0.761718,-1.40625 -2.257812,-2.359375 -3.957031,-2.359375 -2.503906,0 -4.511719,2.023437 -4.511719,4.515625 0,2.488281 2.007813,4.511719 4.511719,4.511719 2.492187,0 4.515625,-2.023438 4.515625,-4.511719 v -0.351563 z m 0,0" id="path120"></path>      </g>      <g id="glyph-1-7">        <path d="M 0.96875,0 H 3.605469 C 6.035156,0 8,-1.964844 8,-4.394531 8,-6.828125 6.035156,-8.792969 3.605469,-8.792969 H 0.96875 Z m 1.417969,-1.332031 v -6.125 h 1.21875 c 1.667969,0 2.972656,1.300781 2.972656,3.0625 0,1.757812 -1.304687,3.0625 -2.972656,3.0625 z m 0,0" id="path123"></path>      </g>      <g id="glyph-1-8">        <path d="M 10.578125,-8.792969 8.660156,-2.140625 6.769531,-8.792969 h -1.625 l -1.875,6.625 -1.90625,-6.625 H -0.160156 L 2.433594,0 H 4.03125 L 5.949219,-7.035156 7.898438,0 h 1.613281 l 2.5625,-8.792969 z m 0,0" id="path126"></path>      </g>      <g id="glyph-1-9">        <path d="M 6.082031,-7.488281 V -8.792969 H 1.039062 V 0 H 6.082031 V -1.304688 H 2.476562 V -3.738281 H 5.597656 V -5.054688 H 2.476562 v -2.433593 z m 0,0" id="path129"></path>      </g>      <g id="glyph-1-10">        <path d="m 5.039062,-3.824219 c 1.101563,-0.234375 1.90625,-1.203125 1.90625,-2.390625 0,-1.433594 -1.113281,-2.578125 -2.535156,-2.578125 H 0.96875 V 0 h 1.433594 v -3.691406 h 1.21875 L 5.949219,0 H 7.5625 Z M 2.402344,-4.9375 v -2.519531 h 1.832031 c 0.746094,0 1.304687,0.511719 1.304687,1.257812 0,0.75 -0.558593,1.261719 -1.304687,1.261719 z m 0,0" id="path132"></path>      </g>      <g id="glyph-1-11">        <path d="m 6.609375,-8.792969 v 3.722657 H 2.402344 V -8.792969 H 0.96875 V 0 H 2.402344 V -3.738281 H 6.609375 V 0 h 1.433594 v -8.792969 z m 0,0" id="path135"></path>      </g>      <g id="glyph-1-12">        <path d="m 5.144531,0.117188 c 2.488281,0 4.511719,-2.023438 4.511719,-4.511719 0,-2.492188 -2.023438,-4.515625 -4.511719,-4.515625 -2.492187,0 -4.515625,2.023437 -4.515625,4.515625 0,2.488281 2.023438,4.511719 4.515625,4.511719 z m 0,-1.421876 c -1.730469,0 -3.09375,-1.347656 -3.09375,-3.089843 0,-1.746094 1.363281,-3.09375 3.09375,-3.09375 1.742188,0 3.089844,1.347656 3.089844,3.09375 0,1.742187 -1.347656,3.089843 -3.089844,3.089843 z m 0,0" id="path138"></path>      </g>      <g id="glyph-1-13">        <path d="m 3.414062,0.117188 c 1.582032,0 2.871094,-0.9375 2.871094,-2.519532 0,-3.121094 -4.160156,-2.359375 -4.160156,-4.164062 0,-0.6875 0.542969,-1.007813 1.1875,-1.007813 0.585938,0 1.113281,0.261719 1.507812,0.761719 L 5.832031,-7.738281 C 5.273438,-8.425781 4.351562,-8.910156 3.3125,-8.910156 c -1.378906,0 -2.625,0.867187 -2.625,2.449218 0,2.914063 4.164062,2.195313 4.164062,4.058594 0,0.71875 -0.601562,1.140625 -1.421874,1.140625 -0.894532,0 -1.570313,-0.496093 -1.875,-1.242187 l -1.230469,0.746094 c 0.511719,1.097656 1.683593,1.875 3.089843,1.875 z m 0,0" id="path141"></path>      </g>      <g id="glyph-2-0"></g>    </g>    <clipPath id="clip-0">      <path d="M 95.105469,61.609375 H 279.60547 V 187.60937 H 95.105469 Z m 0,0" clip-rule="nonzero" id="path147"></path>    </clipPath>  </defs>  <rect x="-132.60547" width="450" fill="#ffffff" y="-99.226562" height="450" fill-opacity="1" id="rect158" style="fill:#000000;fill-opacity:0"></rect>  <g clip-path="url(#clip-0)" id="g166" style="fill:#ffffff;fill-opacity:1;stroke:none;stroke-opacity:1;opacity:1" transform="translate(-95.105469,-61.726562)">    <path fill="rgb(100%, 100%, 100%)" d="M 187.28516,61.726562 95.105469,135.17578 h 24.859371 l 67.36328,-54.433592 67.36719,54.433592 h 24.85547 z m 0.0352,28.3125 -60.57422,48.949218 c 6.01953,27.01953 29.11328,46.80859 56.67969,48.5625 -0.0195,-0.0117 -0.0195,-0.0234 -0.0195,-0.0391 0,-0.004 -0.0117,-0.0234 -0.0117,-0.0312 0,-0.0117 -0.0273,-0.0625 -0.0273,-0.0625 -0.4961,-1.22266 -5.97657,-15.48438 0.73828,-36.03516 -12.39453,1.47656 -20.08985,-3.30078 -24.55469,-8.60547 -4.70312,-5.5664 -5.96484,-11.70703 -5.96484,-11.70703 l -0.19141,-0.92578 0.93359,-0.082 c 1.82813,-0.16797 3.5586,-0.23047 5.19141,-0.21094 11.42969,0.14063 18.21094,4.53906 22.08594,9.23438 2.21875,2.68359 3.5,5.46484 4.22265,7.57031 0.51954,-1.27734 1.07813,-2.57422 1.69141,-3.89844 l -0.14062,-0.0195 -0.20313,-0.35156 c -2.66797,-4.60547 -3.07812,-9.11328 -2.15234,-13.1836 0.92187,-4.0664 3.13672,-7.69531 5.64843,-10.73437 5.02735,-6.07422 11.33204,-9.84375 11.33204,-9.84375 l 1.12109,-0.67187 0.14062,1.30078 c 1.92188,17.64843 -1.73437,26.125 -6.10546,30.14453 -3.53516,3.25 -7.42969,3.46093 -8.8125,3.42578 -0.65625,2.64453 -1.41797,6.41406 -1.8086,10.78125 0.38672,-0.71875 0.79688,-1.44531 1.25,-2.1836 4.25391,-6.88281 11.91016,-14.22656 25.10547,-15.88281 l 0.86719,-0.10156 0.0703,0.86719 c 0,0 0.48047,5.78125 -3.10156,12.3125 -3.34375,6.10547 -10.25,12.8789 -24.1211,16.42578 0.86719,7.69531 3.63282,15.6875 10.1875,21.84765 25.23438,-3.92968 45.4961,-22.9414 51.07813,-47.92187 z m 14.34375,20.867188 c -1.51172,0.97656 -5.6875,3.78125 -9.70312,8.63672 -2.40235,2.90234 -4.46485,6.32031 -5.30469,10.03125 -0.57031,2.5 -0.57031,5.13672 0.23437,7.89453 1.03516,-4.09375 4.65235,-15.38281 14.87891,-25.29687 -0.0352,-0.41797 -0.0664,-0.83594 -0.10547,-1.26563 z m 0.1211,1.49609 c -2.57422,3.32422 -10.21094,14.28125 -11.34375,28.60938 1.46875,-0.24219 3.51562,-0.9375 5.58593,-2.84375 3.61328,-3.32031 6.91407,-10.58594 5.75782,-25.76563 z m -42.61329,19.21875 c -1.15234,-0.004 -2.35546,0.0352 -3.61718,0.11719 0.32422,1.30469 1.49609,5.49219 5.26953,9.96094 3.96094,4.69531 10.5,8.86719 21.25,8.09765 -1.28516,-2.52343 -6.13672,-9.77343 -21.05469,-14.69921 0,0 0.22266,-0.0117 0.625,-0.0117 2.60156,0.0117 12.78906,0.55078 20.58594,7.74218 -0.52735,-0.85937 -1.15625,-1.75781 -1.91016,-2.67578 -3.625,-4.38672 -9.98828,-8.48437 -21.14844,-8.53125 z m 52.9375,5.83203 c -12,1.83594 -18.95312,8.46485 -22.89843,14.84766 -1.29297,2.09375 -2.23047,4.01953 -2.90625,5.69141 0,0.32031 -0.0156,0.63672 -0.0156,0.95703 2.83985,-4.03906 8.27344,-9.30078 18.61328,-13.42969 0,0 -13.53125,7.10938 -17.17187,17.36719 12.37109,-3.51953 18.53906,-9.60938 21.53906,-15.07422 2.85156,-5.20703 2.87109,-9.11328 2.83984,-10.35938 z m 0,0" fill-opacity="1" fill-rule="nonzero" id="path164" style="fill:#ffffff;fill-opacity:1;stroke:none;stroke-opacity:1"></path>  </g>  <g fill="#ffffff" fill-opacity="1" id="g182" transform="translate(-95.105469,-61.726562)">    <use x="78.275932" y="233.74185" xlink:href="#glyph-0-4" xlink:type="simple" xlink:actuate="onLoad" xlink:show="embed" id="use180" width="100%" height="100%"></use>  </g>  <g fill="#ffffff" fill-opacity="1" id="g222" transform="translate(-95.105469,-61.726562)">    <use x="224.06978" y="233.74185" xlink:href="#glyph-0-4" xlink:type="simple" xlink:actuate="onLoad" xlink:show="embed" id="use220" width="100%" height="100%"></use>  </g>  <g fill="#ffffff" fill-opacity="1" id="g258" transform="translate(-95.105469,-61.726562)">    <use x="351.16122" y="233.74185" xlink:href="#glyph-0-4" xlink:type="simple" xlink:actuate="onLoad" xlink:show="embed" id="use256" width="100%" height="100%"></use>  </g>  <g fill="#ffffff" fill-opacity="1" id="g294" transform="translate(-95.105469,-61.726562)">    <use x="210.25388" y="266.93246" xlink:href="#glyph-2-0" xlink:type="simple" xlink:actuate="onLoad" xlink:show="embed" id="use292" width="100%" height="100%"></use>  </g>  <g fill="#ffffff" fill-opacity="1" id="g342" transform="translate(-95.105469,-61.726562)">    <use x="178.40524" y="282.68246" xlink:href="#glyph-2-0" xlink:type="simple" xlink:actuate="onLoad" xlink:show="embed" id="use340" width="100%" height="100%"></use>  </g>  <g fill="#ffffff" fill-opacity="1" id="g358" transform="translate(-95.105469,-61.726562)">    <use x="216.17934" y="282.68246" xlink:href="#glyph-2-0" xlink:type="simple" xlink:actuate="onLoad" xlink:show="embed" id="use356" width="100%" height="100%"></use>  </g>  <g fill="#ffffff" fill-opacity="1" id="g419" transform="translate(-95.105469,-61.726562)">    <use x="130.08908" y="43.699993" xlink:href="#glyph-3-8" xlink:type="simple" xlink:actuate="onLoad" xlink:show="embed" id="use417" width="100%" height="100%"></use>  </g>  <g fill="#ffffff" fill-opacity="1" id="g471" transform="translate(-95.105469,-61.726562)">    <use x="251.64113" y="43.699993" xlink:href="#glyph-3-8" xlink:type="simple" xlink:actuate="onLoad" xlink:show="embed" id="use469" width="100%" height="100%"></use>  </g></svg>',
                customSize: 26,
                filename: "Campaign Logo White SVG.svg",
                name: "favourite-31",
                padding: 7,
                type: "custom"
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
                text: '<p class="brz-fs-lg-1_67 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-m_0_4"><br></p><p class="brz-fs-lg-1_67 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-m_0_4"><span style="color: rgb(234, 236, 237); font-weight: 700; ">NEW SANCTUARY CAMPAIGN</span><br></p><p class="brz-fs-lg-1_18 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_4"><span style="color: rgb(234, 236, 237); font-weight: 400; ">PLANTING AND WATERING THE GOSPEL</span></p><p class="brz-fs-lg-23_68 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_4"><br></p><p class="brz-fs-lg-1_18 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-0"><span style="font-weight: 400; "><span style="font-weight: 400; ">"I planted, Apollos watered, but God gave the growth." 1 Cor 3:6</span></span></p>'
              }
            }
          ]
        }
      }
    ]
  }
};

// Solstice example 51 (Text duplicated inside icons)
const ex51: Data = {
  html: `<div class="text-content text-0 editable" data-id="512765" data-category="text"><div><p style="letter-spacing: -0.1536px; line-height: 21.504px; font-size: 0.6em;"><br></p><p style="letter-spacing: -0.1536px;"><a href="https://www.facebook.com/groups/DooWopMusicHallofFame/" data-location="external" data-detail="https://www.facebook.com/groups/DooWopMusicHallofFame/" data-category="link" target="_blank" class="cloverlinks"><span data-socialicon="roundedfacebook"><span class="socialIconSymbol" aria-hidden="true"></span><span class="sr-only">roundedfacebook</span></span></a> ​&nbsp; <span class="clovercustom" style="font-weight: 400; font-family: 'Open Sans', 'Open Sans Regular', sans-serif; color: rgb(247, 248, 250);">facebook</span><span class="clovercustom" style="color: rgb(247, 248, 250);">&nbsp;</span> &nbsp;<a href="https://twitter.com/globaldoowop" data-location="external" data-category="link" target="_blank" class="cloverlinks" data-detail="https://twitter.com/globaldoowop"></a><a href="http://www.twitter.com/globaldoowop1" data-location="external" data-detail="http://www.twitter.com/globaldoowop1" data-category="link" target="_blank" class="cloverlinks"><span data-socialicon="roundedtwitterbird"><span class="socialIconSymbol" style="color: rgb(53, 224, 227);" aria-hidden="true"></span><span class="sr-only">roundedtwitterbird</span></span></a> <span class="clovercustom" style="color: rgb(245, 178, 10);"> </span> <span class="clovercustom" style="color: rgb(250, 250, 250);">&nbsp;</span><span class="clovercustom" style="font-family: 'Open Sans', 'Open Sans Regular', sans-serif; font-weight: 400;"><span class="clovercustom" style="color: rgb(250, 250, 250);">twitter</span> &nbsp;<a href="/contact-us" data-location="existing" data-detail="38858" data-category="link" target="_self" class="cloverlinks"><span data-socialicon="roundedemail"><span class="socialIconSymbol" style="color: rgb(235, 227, 73);" aria-hidden="true"></span><span class="sr-only">roundedemail</span></span></a>  &nbsp;<span class="clovercustom" style="color: rgb(247, 248, 250);">email </span></span></p><p style="letter-spacing: -0.1536px; line-height: 21.504px;"><br></p><p style="letter-spacing: -0.1536px; line-height: 21.504px;">&nbsp;<a href="https://www.pinterest.com/globaldoowop/" data-location="external" data-detail="https://www.pinterest.com/globaldoowop/" data-category="link" target="_self" class="cloverlinks">&nbsp;</a><a href="/events/meet-ups" data-location="existing" data-detail="56202" data-category="link" target="_self" class="cloverlinks"><span data-socialicon="roundedmeetup"><span class="socialIconSymbol" style="color: rgb(128, 36, 181);" aria-hidden="true"></span><span class="sr-only">roundedmeetup</span></span></a> ​&nbsp;<span class="clovercustom" style="color: rgb(229, 142, 232); font-family: 'Open Sans', 'Open Sans Regular', sans-serif; font-weight: 400;"><span class="clovercustom" style="color: rgb(245, 240, 245);">meetup &nbsp;</span> &nbsp;<a href="/donate" data-location="existing" data-detail="56455" data-category="link" target="_self" class="cloverlinks"><span data-socialicon="roundedheart"><span class="socialIconSymbol" style="color: rgb(18, 204, 77);" aria-hidden="true"></span><span class="sr-only">roundedheart</span></span></a> <span class="clovercustom"> </span>  <span class="clovercustom" style="color: rgb(250, 247, 250);">donate</span></span></p><p style="letter-spacing: -0.1536px; line-height: 21.504px;"><br></p><p style="font-family: 'Open Sans', 'Open Sans Regular', sans-serif; font-weight: 400;"><a href="https://www.pinterest.com/globaldoowop/" data-location="external" data-detail="https://www.pinterest.com/globaldoowop/" data-category="link" target="_blank" class="cloverlinks"><span data-socialicon="roundedaddme"><span class="socialIconSymbol" style="font-weight: 400; color: rgb(207, 19, 16);" aria-hidden="true"></span><span class="sr-only">roundedaddme</span></span></a>  <span class="clovercustom" style="font-weight: 400; color: rgb(240, 241, 245);">pinterest</span></p><p><br></p><p style="letter-spacing: -0.1536px; line-height: 21.504px;"><br></p><p style="letter-spacing: -0.1536px; line-height: 21.504px;"><span style="font-family: 'Open Sans', 'Open Sans Regular', sans-serif; font-size: 0.8em; letter-spacing: -0.1536px; line-height: 1.4em;">The Global Doo Wop Coalition &nbsp;&nbsp;</span></p><p style="letter-spacing: -0.1536px; font-weight: 400; font-family: 'Open Sans', 'Open Sans Regular', sans-serif; font-size: 0.8em;"> 10153 Riverside Drive &nbsp;Suite 405</p><p style="letter-spacing: -0.1536px; font-weight: 400; font-family: 'Open Sans', 'Open Sans Regular', sans-serif; font-size: 0.8em;"> Los Angeles, CA &nbsp;91602</p><p style="letter-spacing: -0.1536px; font-weight: 400; font-family: 'Open Sans', 'Open Sans Regular', sans-serif; font-size: 0.8em;"><br></p><p style="letter-spacing: -0.1536px; font-weight: 400; font-family: 'Open Sans', 'Open Sans Regular', sans-serif; font-size: 0.8em;">globaldoowop.org &nbsp; &nbsp;1-800-495-1964</p><p style="letter-spacing: -0.1536px; font-weight: 400; line-height: 21.504px; font-family: 'Open Sans', 'Open Sans Regular', sans-serif; font-size: 0.6667em;"><br></p><p style="letter-spacing: -0.1536px; font-weight: 400; line-height: 21.504px; font-family: 'Open Sans', 'Open Sans Regular', sans-serif; font-size: 0.8em;">A 501C3 nonprofit organization dedicated to </p><p style="letter-spacing: -0.1536px; font-weight: 400; line-height: 21.504px; font-family: 'Open Sans', 'Open Sans Regular', sans-serif; font-size: 0.8em;">promoting, preserving, perpetuating </p><p style="letter-spacing: -0.1536px; font-weight: 400; line-height: 21.504px; font-family: 'Open Sans', 'Open Sans Regular', sans-serif; font-size: 0.8em;">and developing doo wop music. &nbsp; &nbsp;</p><p style="letter-spacing: -0.1536px; font-weight: 700; line-height: 21.504px; font-family: 'Open Sans', 'Open Sans Regular', sans-serif; font-size: 0.6667em;">EIN: &nbsp;80 0825662</p><p style="letter-spacing: -0.1536px; font-weight: 700; line-height: 21.504px; font-family: 'Open Sans', 'Open Sans Regular', sans-serif; font-size: 0.6667em;"><br></p><p style="letter-spacing: -0.1536px; font-weight: 400; line-height: 21.504px; color: rgb(3, 3, 0); font-family: 'Open Sans', 'Open Sans Regular', sans-serif; font-size: 0.8em;"><br></p><p style="letter-spacing: -0.1536px; line-height: 21.504px; font-size: 0.4em;">​&nbsp; &nbsp; &nbsp;</p></div></div>`,
  entry: { ...entry, selector: '[data-id="512765"]' },
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
                text: '<p class="brz-fs-lg-0_6 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><br></p>'
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
          horizontalAlign: undefined,
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                customSize: 26,
                linkExternal:
                  "https://www.facebook.com/groups/DooWopMusicHallofFame/",
                linkExternalBlank: "on",
                linkType: "external",
                name: "facebook-square",
                padding: 7,
                type: "fa"
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 400; color: rgb(247, 248, 250); ">facebook</span></p>'
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
          horizontalAlign: undefined,
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                colorHex: "#35e0e3",
                colorOpacity: 1,
                colorPalette: "",
                customSize: 26,
                hoverColorHex: "#35e0e3",
                hoverColorOpacity: 0.8,
                hoverColorPalette: "",
                linkExternal: "http://www.twitter.com/globaldoowop1",
                linkExternalBlank: "on",
                linkType: "external",
                name: "twitter-square",
                padding: 7,
                type: "fa"
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 400; "><span style="color: rgb(250, 250, 250); font-weight: 400; ">twitter</span></span></p>'
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
          horizontalAlign: undefined,
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                colorHex: "#ebe349",
                colorOpacity: 1,
                colorPalette: "",
                customSize: 26,
                hoverColorHex: "#ebe349",
                hoverColorOpacity: 0.8,
                hoverColorPalette: "",
                linkExternal: "/contact-us",
                linkExternalBlank: "on",
                linkType: "external",
                name: "envelope-square",
                padding: 7,
                type: "fa"
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 400; "><span style="color: rgb(247, 248, 250); font-weight: 400; ">email </span></span></p>'
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><br></p>'
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
          horizontalAlign: undefined,
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                colorHex: "#8024b5",
                colorOpacity: 1,
                colorPalette: "",
                customSize: 26,
                hoverColorHex: "#8024b5",
                hoverColorOpacity: 0.8,
                hoverColorPalette: "",
                linkExternal: "/events/meet-ups",
                linkExternalBlank: "on",
                linkType: "external",
                name: "meetup",
                padding: 7,
                type: "fa"
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(229, 142, 232); font-weight: 400; "><span style="color: rgb(245, 240, 245); font-weight: 400; ">meetup &nbsp;</span></span></p>'
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
          horizontalAlign: undefined,
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                colorHex: "#12cc4d",
                colorOpacity: 1,
                colorPalette: "",
                customSize: 26,
                hoverColorHex: "#12cc4d",
                hoverColorOpacity: 0.8,
                hoverColorPalette: "",
                linkExternal: "/donate",
                linkExternalBlank: "on",
                linkType: "external",
                name: "heart",
                padding: 7,
                type: "fa"
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(229, 142, 232); font-weight: 400; "><span style="color: rgb(250, 247, 250); font-weight: 400; ">donate</span></span></p>'
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><br></p>'
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
          horizontalAlign: undefined,
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                colorHex: "#cf1310",
                colorOpacity: 1,
                colorPalette: "",
                customSize: 26,
                hoverColorHex: "#cf1310",
                hoverColorOpacity: 0.8,
                hoverColorPalette: "",
                linkExternal: "https://www.pinterest.com/globaldoowop/",
                linkExternalBlank: "on",
                linkType: "external",
                name: "plus-circle",
                padding: 7,
                type: "fa"
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 400; color: rgb(240, 241, 245); ">pinterest</span></p>'
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><br></p><p class="brz-fs-lg-0_8 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><span style="">The Global Doo Wop Coalition &nbsp;&nbsp;</span></p><p class="brz-fs-lg-0_8 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: 400; "> 10153 Riverside Drive &nbsp;Suite 405</span></p><p class="brz-fs-lg-0_8 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: 400; "> Los Angeles, CA &nbsp;91602</span></p><p class="brz-fs-lg-0_8 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><br></p><p class="brz-fs-lg-0_8 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: 400; ">globaldoowop.org &nbsp; &nbsp;1-800-495-1964</span></p><p class="brz-fs-lg-0_67 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><br></p><p class="brz-fs-lg-0_8 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: 400; ">A 501C3 nonprofit organization dedicated to </span></p><p class="brz-fs-lg-0_8 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: 400; ">promoting, preserving, perpetuating </span></p><p class="brz-fs-lg-0_8 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: 400; ">and developing doo wop music. &nbsp; &nbsp;</span></p><p class="brz-fs-lg-0_67 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: 700; ">EIN: &nbsp;80 0825662</span></p><p class="brz-fs-lg-0_67 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><br></p><p class="brz-fs-lg-0_8 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><br></p><p class="brz-fs-lg-0_4 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><span>​&nbsp; &nbsp; &nbsp;</span></p>'
              }
            }
          ]
        }
      }
    ]
  }
};

// Majesty example 52 (Extra spacing for text)
const ex52: Data = {
  html: `<div class="text-content text-2 editable" data-id="22659650" data-category="text"><div><p style="text-align: justify;">If you have a college ID and are a current student, then you are invited to come to the cafe to study and enjoy free cafe drinks.&nbsp; Our drink menu includes fresh grind drip coffee, french press, espresso, teas, lattes, pour overs, smoothies, frappes and many other creations by our experienced barista.&nbsp; You will find that the cafe is a safe and quiet place to study.&nbsp; Cafe hours during Marshall University's spring and fall semesters are as follows:</p><p style="text-align: justify;"><br></p><p style="text-align: center;">Monday 9am - 2pm</p><p style="text-align: center;">Tuesday 1pm - 6pm</p><p style="text-align: center;">Wednesday 11am - 4pm</p><p style="text-align: center;">Thursday 9am - 2pm</p><p style="text-align: center;"><br></p><p style="text-align: center;">When Marshall University is not in session during the Spring &amp; Fall semesters,&nbsp;</p><p style="text-align: center;">the cafe will be closed. &nbsp;Please note the following 2025 dates:</p><p style="text-align: center;"><br></p><p style="text-align: center;">August 18th: &nbsp;OPENING day for Fall Semester<br></p><p style="text-align: center;">September 1st: &nbsp;CLOSED for Labor Day</p><p style="text-align: center;">October 9th: &nbsp;CLOSED for October Break</p><p style="text-align: center;">November 24th - 27th: &nbsp;CLOSED for Thanksgiving Break</p><p style="text-align: center;">December 5th: &nbsp;CLOSES &nbsp;for Christmas Break</p><p style="text-align: center;"><br></p><p style="line-height: 0em; font-size: 1em;"><br></p><p style="line-height: 0em; font-size: 1em;"><br></p><p style="line-height: 0em; font-size: 1em;"><br></p><p style="line-height: 0em; font-size: 1em;"><br></p><p style="line-height: 0em; font-size: 1em;"><br></p><p style="line-height: 0em; font-size: 1em;"><br></p><p style="line-height: 0em; font-size: 1em;"><br></p><p style="line-height: 0em; font-size: 1em;"><br></p><p style="line-height: 0em; font-size: 1em;"><br></p><p style="line-height: 0em; font-size: 1em;"><br></p><p style="line-height: 0em; font-size: 1em;"><br></p><p style="line-height: 0em; font-size: 1em;"><br></p><p style="line-height: 0em; font-size: 1em;"><br></p><p style="line-height: 0em; font-size: 1em;"><br></p><p style="line-height: 0em; font-size: 1em;"><br></p><p style="line-height: 0em; font-size: 1em;"><br></p><p style="line-height: 0em; font-size: 1em;"><br></p><p style="line-height: 0em; font-size: 1em;"><br></p><p style="line-height: 0em; font-size: 1em;"><br></p><p style="line-height: 0em;"><br></p></div></div>`,
  entry: { ...entry, selector: '[data-id="22659650"]' },
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-justify brz-ls-lg-NaN_0"><span>If you have a college ID and are a current student, then you are invited to come to the cafe to study and enjoy free cafe drinks.&nbsp; Our drink menu includes fresh grind drip coffee, french press, espresso, teas, lattes, pour overs, smoothies, frappes and many other creations by our experienced barista.&nbsp; You will find that the cafe is a safe and quiet place to study.&nbsp; Cafe hours during Marshall University\'s spring and fall semesters are as follows:</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-justify brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span>Monday 9am - 2pm</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span>Tuesday 1pm - 6pm</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span>Wednesday 11am - 4pm</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span>Thursday 9am - 2pm</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span>When Marshall University is not in session during the Spring &amp; Fall semesters,&nbsp;</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span>the cafe will be closed. &nbsp;Please note the following 2025 dates:</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span>August 18th: &nbsp;OPENING day for Fall Semester</span><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span>September 1st: &nbsp;CLOSED for Labor Day</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span>October 9th: &nbsp;CLOSED for October Break</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span>November 24th - 27th: &nbsp;CLOSED for Thanksgiving Break</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span>December 5th: &nbsp;CLOSES &nbsp;for Christmas Break</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p>'
              }
            }
          ]
        }
      }
    ]
  }
};

// Majesty example 53_1 (Text with icons loose styling)
const ex53_1: Data = {
  html: `<div class="text-content text-2 editable" data-id="58980" data-category="text"><div><p style="font-family: Asap, sans-serif; font-weight: 400; color: rgb(120, 120, 120);"
<span style="font-size: 12pt; line-height: 115%; font-weight: 200;" class="clovercustom">Small
groups are an important part of TLC Church; in fact, you haven’t fully
experienced the church until you’ve experienced our small groups called
Hotspots. Hotspots allow you to meet other members and develop relationships
that will spur your relationship with God.</span>&nbsp;</p><p style="font-family: Asap, sans-serif; font-weight: 400; color: rgb(120, 120, 120); font-size: 0.8889em;"><a href="http://theliving.org/hotspots" data-location="external" data-detail="http://theliving.org/hotspots" data-category="link" target="_blank" class="cloverlinks" style="font-weight: 700; color: rgb(120, 120, 120);"><span class="clovercustom" style="font-family: Asap, sans-serif;"><span data-icon="hand-point-right"><span class="clovericons far" aria-hidden="true"></span></span>  Click here</span></a><span class="clovercustom" style="font-family: Asap, sans-serif; font-weight: 400;"> for our Hotspot information.</span><span class="clovercustom" style="font-family: Asap, sans-serif; font-weight: 400;">&nbsp;&nbsp;&nbsp;</span><br></p></div></div>`,
  entry: { ...entry, selector: '[data-id="58980"]' },
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
                text: '<p <span="" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(120, 120, 120); font-weight: 400; ">Small groups are an important part of TLC Church; in fact, you haven’t fully experienced the church until you’ve experienced our small groups called Hotspots. Hotspots allow you to meet other members and develop relationships that will spur your relationship with God.&nbsp;</span></p>'
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
                name: "hand-point-right",
                padding: 7,
                type: "fa",
                linkExternal: "http://theliving.org/hotspots",
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><a data-location="external" data-detail="http://theliving.org/hotspots" data-category="link" target="_blank" style="font-weight: 700; color: rgb(120, 120, 120); " data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22http%3A%2F%2Ftheliving.org%2Fhotspots%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span style="color: rgb(120, 120, 120); font-weight: 700; "> Click here</span></a></p>'
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 400; "> for our Hotspot information.</span></p>'
              }
            }
          ]
        }
      }
    ]
  }
};

// Majesty example 53_2 (Text with icons loose styling)
const ex53_2: Data = {
  html: `<div class="text-content text-2 editable" data-id="58984" data-category="text"><div><p>
</p><p style="font-family: Asap, sans-serif; font-weight: 400; color: rgb(120, 120, 120);"><span style="font-size: 12pt; line-height: 115%; font-weight: 200;" class="clovercustom">One of the easiest things to do
at TLC Church is to participate in a small group. Hotspots meet throughout greater
Seattle area and provide a place for Bible study, fellowship, and support.
Wherever you live, there’s a Hotspot <span class="clovercustom" style="font-weight: 200;">&nbsp;</span>near you. <span data-icon="hand-point-right"><span class="clovericons far" aria-hidden="true"></span></span> &nbsp;<a href="http://theliving.org/hotspots" data-location="external" data-detail="http://theliving.org/hotspots" data-category="link" target="_blank" class="cloverlinks" style="font-weight: 700; color: rgb(120, 120, 120);">Click here</a> for&nbsp; our online directory and
visit a group this week.</span></p></div></div>`,
  entry: { ...entry, selector: '[data-id="58984"]' },
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
                text: ""
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
                text: '<p class="brz-fs-lg-12 brz-ff-lato brz-ft-upload brz-fw-lg-200 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 200; ">One of the easiest things to do at TLC Church is to participate in a small group. Hotspots meet throughout greater Seattle area and provide a place for Bible study, fellowship, and support. Wherever you live, there’s a Hotspot</span></p>'
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
                text: '<p class="brz-fs-lg-12 brz-ff-lato brz-ft-upload brz-fw-lg-200 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 200; ">near you.</span></p>'
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
          horizontalAlign: undefined,
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                customSize: 26,
                name: "hand-point-right",
                padding: 7,
                type: "fa"
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 200; "><a data-location="external" data-detail="http://theliving.org/hotspots" data-category="link" target="_blank" style="font-weight: 700; color: rgb(120, 120, 120); " data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22http%3A%2F%2Ftheliving.org%2Fhotspots%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span style="color: rgb(120, 120, 120); font-weight: 700; ">Click here</span></a></span></p>'
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
                text: '<p class="brz-fs-lg-12 brz-ff-lato brz-ft-upload brz-fw-lg-200 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 200; ">for&nbsp; our online directory and visit a group this week.</span></p>'
              }
            }
          ]
        }
      }
    ]
  }
};

// Voyage example 54 (Voyage Text loose styling)
const ex54: Data = {
  html: `<div class="text-content text-0 editable" data-id="3633521" data-category="text"><div><p style="color: rgb(40, 77, 153); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-weight: 700; font-size: 2.0408em;">H<span class="clovercustom">EYWORTH CHRISTIAN CHURCH</span><br></p><p style="font-size: 1.6582em; color: rgb(40, 77, 153); font-family: &quot;Clear Sans&quot;, &quot;Clear Sans Regular&quot;, sans-serif; font-weight: 500;"><span class="clovercustom" style="font-size: 0.8461em;">308 North Vine Street</span><span class="clovercustom" style="letter-spacing: -0.1568px;">&nbsp;</span><span data-icon="circle"><span class="clovericons fas" style="letter-spacing: -0.1568px; font-size: 0.4615em;" aria-hidden="true"></span></span><span class="clovercustom" style="letter-spacing: -0.1568px;"> &nbsp;</span><span class="clovercustom" style="font-size: 0.8461em;">Heyworth, IL 61745</span><span class="clovercustom" style="letter-spacing: -0.1568px;">&nbsp;</span><span data-icon="circle"><span class="clovericons fas" style="letter-spacing: -0.1568px; font-size: 0.4615em;" aria-hidden="true"></span></span><span class="clovercustom" style="letter-spacing: -0.1568px;"> </span><span style="font-size: 1em; letter-spacing: -0.01em;">&nbsp;</span><span style="letter-spacing: -0.1568px; font-size: 0.8846em;">309.473.2771</span></p><p style="color: rgb(40, 77, 153); font-family: &quot;Clear Sans&quot;, &quot;Clear Sans Regular&quot;, sans-serif; font-weight: 500; font-size: 1.4031em;">Sunday Worship Service<span class="clovercustom" style="letter-spacing: -0.1568px;">&nbsp;</span><span data-icon="circle"><span class="clovericons fas" style="letter-spacing: -0.1568px; font-size: 0.5454em;" aria-hidden="true"></span></span><span class="clovercustom" style="letter-spacing: -0.1568px;"> </span><span class="clovercustom" style="letter-spacing: -0.01em;">&nbsp;</span><span style="letter-spacing: -0.01em;">9:30 am</span></p><p style="color: rgb(40, 77, 153); font-family: &quot;Clear Sans&quot;, &quot;Clear Sans Regular&quot;, sans-serif; font-weight: 500; font-size: 1.4031em;"><br></p><p style="color: rgb(40, 77, 153); font-family: &quot;Clear Sans&quot;, &quot;Clear Sans Regular&quot;, sans-serif; font-weight: 500; font-size: 1.0842em;">2024 © Heyworth Christian Church. All rights reserved.&nbsp;<br></p></div></div>`,
  entry: { ...entry, selector: '[data-id="3633521"]' },
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
                text: '<p class="brz-fs-lg-2_04 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(40, 77, 153); font-weight: 700; ">H</span><span style="color: rgb(40, 77, 153); font-weight: 700; ">EYWORTH CHRISTIAN CHURCH</span><br></p>'
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
                text: '<p class="brz-fs-lg-0_85 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="">308 North Vine Street</span></p>'
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
                name: "circle",
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
                text: '<p class="brz-fs-lg-0_85 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="">Heyworth, IL 61745</span></p>'
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
                name: "circle",
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
                text: '<p class="brz-fs-lg-0_88 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><span style="">309.473.2771</span></p>'
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
                text: '<p class="brz-fs-lg-1_4 brz-ff-lato brz-ft-upload brz-fw-lg-500 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(40, 77, 153); font-weight: 500; ">Sunday Worship Service</span></p>'
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
                name: "circle",
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_0"><span style="">9:30 am</span></p>'
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
                text: '<p class="brz-fs-lg-1_4 brz-ff-lato brz-ft-upload brz-fw-lg-500 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_08 brz-ff-lato brz-ft-upload brz-fw-lg-500 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(40, 77, 153); font-weight: 500; ">2024 © Heyworth Christian Church. All rights reserved.&nbsp;</span><br></p>'
              }
            }
          ]
        }
      }
    ]
  }
};

// Majesty example 55 (Voyage Buttons missing in lists)
const ex55: Data = {
  html: `<div class="text-content text-1 editable" data-id="11277819" data-category="text"><div><p><br></p><p style="text-align: left;">We take great pride in our wide selection of mouth-watering baked goods. At Achenbach's, you will always get the "Baker's Dozen" - 13 items for the price of twelve! Click the links below to see our prices. Prices shown here are on site bakery retail prices only.
</p><p style="text-align: left;"><br></p><p style="text-align: left;">
</p><ul style="color: rgba(26, 21, 26, 0.7); font-family: Lato, &quot;Lato Light&quot;, sans-serif; font-style: normal; font-weight: 600;"><li><a href="https://s3.amazonaws.com/media.cloversites.com/5e/5e332201-6805-411f-b562-6228e351b32e/documents/Price_Sheet.doc" data-location="upload" data-button="true" data-detail="Price_Sheet.doc" data-category="document" target="_blank" class="cloverlinks sites-button" role="button">PRODUCT LIST AND PRICES </a><a href="https://s3.amazonaws.com/media.cloversites.com/5e/5e332201-6805-411f-b562-6228e351b32e/documents/Detailed_RETAIL_Price_List_040125.xls" data-location="upload" data-button="true" data-detail="Detailed_RETAIL_Price_List_040125.xls" data-category="document" target="_blank" class="cloverlinks sites-button" role="button">detailed list in excel format</a></li></ul><p style="text-align: left; color: rgb(1, 60, 52); font-size: 1.25em; font-weight: 300;">Cakes</p><p style="text-align: left; font-weight: 600;">
</p><p style="text-align: left; font-weight: 300;">Cakes are our specialty. No matter what the occasion is, we take pleasure in creating a master piece that fits your theme. Achenbach's cakes are just as beautiful to look as they are delicious to eat!
</p><p style="text-align: left;"><br></p><p style="text-align: left;">
</p><ul style="color: rgba(26, 21, 26, 0.7); font-family: Lato, &quot;Lato Light&quot;, sans-serif; font-style: normal; font-weight: 300;"><li><span class="clovercustom" style="letter-spacing: 0em; font-size: 0.94em; font-weight: 600;"><a href="https://s3.amazonaws.com/media.cloversites.com/5e/5e332201-6805-411f-b562-6228e351b32e/documents/Cake_Price_Listing.doc" data-location="upload" data-button="true" data-detail="Cake_Price_Listing.doc" data-category="document" target="_blank" class="cloverlinks sites-button" role="button">CAKE LIST AND PRICES</a></span><br></li></ul><p style="text-align: left;"><br></p><p style="text-align: left;">
</p><p style="text-align: left;">
</p><p style="text-align: left;">
</p><p style="text-align: left; color: rgb(1, 60, 52); font-size: 1.25em;">Fundraising</p><p style="text-align: left;">Click <a style="font-weight: 600;" href="https://s3.amazonaws.com/media.cloversites.com/5e/5e332201-6805-411f-b562-6228e351b32e/documents/FUNDRAISER_INFORMATION_040125.doc" data-location="upload" data-button="true" data-detail="FUNDRAISER_INFORMATION_040125.doc" data-category="document" target="_blank" class="cloverlinks sites-button" role="button">here</a> for information on Achenbach's special deals for groups involved in fund raising for their church, organization or club!</p></div></div>`,
  entry: { ...entry, selector: '[data-id="11277819"]' },
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>We take great pride in our wide selection of mouth-watering baked goods. At Achenbach\'s, you will always get the "Baker\'s Dozen" - 13 items for the price of twelve! Click the links below to see our prices. Prices shown here are on site bakery retail prices only. </span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p>'
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
          horizontalAlign: "center",
          items: [
            {
              type: "Button",
              value: {
                _id: "1",
                _styles: ["button"],
                borderStyle: "none",
                fontFamily: "lato",
                fontFamilyType: "upload",
                fontStyle: "",
                iconName: "",
                lineHeight: 1.3,
                linkExternal:
                  "https://s3.amazonaws.com/media.cloversites.com/5e/5e332201-6805-411f-b562-6228e351b32e/documents/Price_Sheet.doc",
                linkExternalBlank: "on",
                linkType: "external",
                mobileFontStyle: "",
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontStyle: "",
                tabletLineHeight: 1.2,
                text: "PRODUCT LIST AND PRICES"
              }
            },
            {
              type: "Button",
              value: {
                _id: "1",
                _styles: ["button"],
                borderStyle: "none",
                fontFamily: "lato",
                fontFamilyType: "upload",
                fontStyle: "",
                iconName: "",
                lineHeight: 1.3,
                linkExternal:
                  "https://s3.amazonaws.com/media.cloversites.com/5e/5e332201-6805-411f-b562-6228e351b32e/documents/Detailed_RETAIL_Price_List_040125.xls",
                linkExternalBlank: "on",
                linkType: "external",
                mobileFontStyle: "",
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontStyle: "",
                tabletLineHeight: 1.2,
                text: "detailed list in excel format"
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
                text: '<p class="brz-fs-lg-1_25 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(1, 60, 52); font-weight: 300; ">Cakes</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 300; ">Cakes are our specialty. No matter what the occasion is, we take pleasure in creating a master piece that fits your theme. Achenbach\'s cakes are just as beautiful to look as they are delicious to eat! </span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p>'
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
                borderStyle: "none",
                fontFamily: "lato",
                fontFamilyType: "upload",
                fontStyle: "",
                iconName: "",
                lineHeight: 1.3,
                linkExternal:
                  "https://s3.amazonaws.com/media.cloversites.com/5e/5e332201-6805-411f-b562-6228e351b32e/documents/Cake_Price_Listing.doc",
                linkExternalBlank: "on",
                linkType: "external",
                mobileFontStyle: "",
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontStyle: "",
                tabletLineHeight: 1.2,
                text: "CAKE LIST AND PRICES"
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1_25 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(1, 60, 52); ">Fundraising</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Click </span><a style="font-weight: 600; " data-location="upload" data-button="true" data-detail="FUNDRAISER_INFORMATION_040125.doc" data-category="document" target="_blank" role="button" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fs3.amazonaws.com%2Fmedia.cloversites.com%2F5e%2F5e332201-6805-411f-b562-6228e351b32e%2Fdocuments%2FFUNDRAISER_INFORMATION_040125.doc%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span style="font-weight: 600; ">here</span></a><span> for information on Achenbach\'s special deals for groups involved in fund raising for their church, organization or club!</span></p>'
              }
            }
          ]
        }
      }
    ]
  }
};

// Voyage example 56 (Icon missing link)
const ex56: Data = {
  html: `<div class="text-content text-0 editable" data-id="8704564" data-category="text"><div><p style="font-weight: 600; color: rgb(0, 0, 0); font-size: 1.0667em;">CONTACT US:&nbsp;&nbsp;</p><p style="font-weight: 600; color: rgb(0, 0, 0); font-size: 1.0667em;">Calvary Baptist Church<br></p><p style="font-weight: 600; color: rgb(0, 0, 0); font-size: 1.0667em;">2407 Broadway, Yankton, SD 57078</p><p style="font-weight: 600; color: rgb(0, 0, 0); font-size: 1.0667em;">(605) 665-5594</p><p style="font-weight: 600; color: rgb(0, 0, 0); font-size: 1.0667em;"><a href="mailto:secretary@cbchurch.com" data-location="email" data-detail="secretary@cbchurch.com" data-category="link" target="_self" class="cloverlinks" style="font-weight: 600; color: rgb(167, 130, 171);">secretary@cbchurch.com</a></p><p style="font-weight: 600; color: rgb(0, 0, 0); font-size: 1.0667em;">Office Hours: 9:00am<span class="clovercustom">&nbsp;</span><span class="clovercustom">–</span><span class="clovercustom">&nbsp;</span>1:00pm, Monday<span class="clovercustom" style="font-size: 16.043167114257813px; font-style: normal; font-weight: 600; letter-spacing: normal; text-align: center;"><span class="clovercustom">&nbsp;</span><span class="clovercustom"><span style="font-size: 16.043167px;" class="clovercustom">–</span>&nbsp;</span></span>Friday</p><p style="font-weight: 600; color: rgb(0, 0, 0); font-size: 1.0667em;"><br></p><p style="font-family: Lato, &quot;Lato Light&quot;, sans-serif; font-weight: 300; font-size: 1.4628em;"><a style="font-weight: 300;" href="https://www.facebook.com/CalvaryBaptistChurchYankton/" data-location="external" data-detail="https://www.facebook.com/CalvaryBaptistChurchYankton/" data-category="link" target="_blank" class="cloverlinks"><span data-socialicon="roundedfacebook"><span class="socialIconSymbol" style="color: rgb(71, 89, 147);" aria-hidden="true"></span></span></a><span class="clovercustom" style="font-size: 22.000511169433594px; font-style: normal; font-weight: 300; letter-spacing: normal; text-align: center;">  &nbsp;</span><a style="font-weight: 300; color: rgb(194, 47, 135);" href="https://www.instagram.com/calvary_yankton/" data-location="external" data-detail="https://www.instagram.com/calvary_yankton/" data-category="link" target="_blank" class="cloverlinks"><span data-socialicon="roundedinstagram"><span class="socialIconSymbol" aria-hidden="true"></span></span></a><span class="clovercustom" style="font-size: 22.000511169433594px; font-style: normal; font-weight: 300; letter-spacing: normal; text-align: center; color: rgb(255, 0, 0);"><a style="font-size: 22.000511169433594px; font-style: normal; font-weight: 300; letter-spacing: normal; text-align: center; color: rgb(0, 166, 227);" href="https://twitter.com/home?lang=en" data-location="external" data-detail="https://twitter.com/home?lang=en" data-category="link" target="_blank" class="cloverlinks"><span class="clovercustom" style="font-size: 22.000511169433594px; font-style: normal; font-weight: 300; letter-spacing: normal; text-align: center;"> &nbsp;</span></a><a href="https://www.youtube.com/channel/UCepa_vIqFOeD45nxFEx1Ykg" data-location="external" data-detail="https://www.youtube.com/channel/UCepa_vIqFOeD45nxFEx1Ykg" data-category="link" target="_blank" class="cloverlinks"> </a><a href="https://www.youtube.com/channel/UCepa_vIqFOeD45nxFEx1Ykg" data-location="external" data-detail="https://www.youtube.com/channel/UCepa_vIqFOeD45nxFEx1Ykg" data-category="link" target="_blank" class="cloverlinks" style="color: rgb(255, 0, 0);"><span class="clovercustom" style="font-size: 22.000511169433594px; font-style: normal; font-weight: 300; letter-spacing: normal; text-align: center;"><span data-socialicon="roundedyoutube"><span class="socialIconSymbol" aria-hidden="true"></span></span> </span> </a></span></p><p style="font-weight: 600; color: rgb(0, 0, 0); font-size: 1.0667em;"><br></p><p style="font-weight: 600; color: rgb(0, 0, 0); font-size: 1.0667em;"><span class="clovercustom" style="color: rgb(169, 130, 173);">Sunday Services:&nbsp;</span>Worship Service<span class="clovercustom" style="font-size: 16.043167114257813px; font-style: normal; font-weight: 600; letter-spacing: normal; text-align: center;"><span class="clovercustom">&nbsp;</span>–<span class="clovercustom"> 9:00am &amp;&nbsp;</span></span>10:30am</p><p style="font-weight: 600; color: rgb(0, 0, 0); font-size: 1.0667em;"><span class="clovercustom" style="color: rgb(167, 130, 171);">Wednesday Evenings:&nbsp;</span>Awana<span class="clovercustom" style="font-size: 16.043167114257813px; font-style: normal; font-weight: 600; letter-spacing: normal; text-align: center;"><span class="clovercustom">&nbsp;&amp; Rev 56 </span>–<span class="clovercustom">&nbsp;</span></span>6:30pm <span style="letter-spacing: 0em; font-size: 0.9973em;">(Sept-May)&nbsp;</span></p><p><span class="clovercustom" style="font-size: 1.0667em; letter-spacing: 0em; color: rgb(0, 0, 0); font-weight: 600;"><span style="font-style: normal; font-weight: 600; letter-spacing: normal; text-align: start; text-decoration: none; font-size: 0.9973em;" class="clovercustom">Calvary Student Ministry</span><span class="clovercustom" style="font-weight: 600; font-size: 0.9973em;">&nbsp;– 6:30</span></span><span class="clovercustom" style="color: rgb(0, 0, 0); font-weight: 600;"><span class="clovercustom" style="font-size: 0.9973em;"><span class="clovercustom" style="font-size: 1.0667em;">pm</span> <span class="clovercustom" style="font-size: 1.0667em;">(Sept-May /</span> <span class="clovercustom" style="font-size: 1.0667em;">summer varies, see </span></span><a href="/ministries/students" data-location="existing" data-button="false" data-detail="516769" data-category="link" target="_self" class="cloverlinks">Student Ministry</a><span class="clovercustom" style="font-size: 1.0667em;">)</span><br></span><span class="clovercustom" style="font-size: 1.0638em; font-weight: 600; color: rgb(0, 0, 0);">Adult Discipleship Group (Bible Study)<span class="clovercustom" style="font-size: 16.043167114257813px; font-style: normal; font-weight: 600; letter-spacing: normal; text-align: center;"><span class="clovercustom">&nbsp;</span>–<span class="clovercustom">&nbsp;</span></span>6:30pm (Sept-May)</span></p><p><br></p><p style="line-height: 1.5em;"><span class="clovercustom" style="font-weight: 600; color: rgb(0, 0, 0);">Online Giving: &nbsp;</span><a class="socialIconLink cloverlinks" href="https://calvary-baptist-yankton.churchcenter.com/giving" data-location="external" data-button="false" data-detail="https://calvary-baptist-yankton.churchcenter.com/giving" data-category="link" target="_blank"><span data-socialicon="roundeddollar"><span class="socialIconSymbol" aria-hidden="true"></span></span></a> </p><p style="color: rgb(36, 64, 31); line-height: 1.5em;"><br></p><p style="font-family: Lato, &quot;Lato Light&quot;, sans-serif; font-weight: 300;">Calvary Baptist is a part of <a style="" href="https://www.convergeheartland.org/" data-location="external" data-detail="https://www.convergeheartland.org/" data-category="link" target="_blank" class="cloverlinks">Converge Heartland</a> and <a style="color: rgb(181, 145, 184);" href="https://converge.org/" data-location="external" data-detail="https://converge.org/" data-category="link" target="_blank" class="cloverlinks">Converge Worldwide</a>.</p><p><br></p><p></p></div></div>`,
  entry: { ...entry, selector: '[data-id="8704564"]' },
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
                text: '<p class="brz-fs-lg-1_07 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(0, 0, 0); font-weight: 600; ">CONTACT US:&nbsp;&nbsp;</span></p><p class="brz-fs-lg-1_07 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(0, 0, 0); font-weight: 600; ">Calvary Baptist Church</span><br></p><p class="brz-fs-lg-1_07 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(0, 0, 0); font-weight: 600; ">2407 Broadway, Yankton, SD 57078</span></p><p class="brz-fs-lg-1_07 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(0, 0, 0); font-weight: 600; ">(605) 665-5594</span></p><p class="brz-fs-lg-1_07 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><a data-location="email" data-detail="secretary@cbchurch.com" data-category="link" target="_self" style="font-weight: 600; color: rgb(167, 130, 171); " data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22mailto%3Asecretary%40cbchurch.com%22%2C%22externalBlank%22%3A%22off%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span style="color: rgb(167, 130, 171); font-weight: 600; ">secretary@cbchurch.com</span></a></p><p class="brz-fs-lg-16_04 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(0, 0, 0); font-weight: 600; ">Office Hours: 9:00am</span><span>&nbsp;</span><span style="color: rgb(0, 0, 0); font-weight: 600; ">–</span><span>&nbsp;</span><span style="color: rgb(0, 0, 0); font-weight: 600; ">1:00pm, Monday</span><span style="font-weight: 600; "><span>&nbsp;</span><span><span style="">–</span>&nbsp;</span></span><span style="color: rgb(0, 0, 0); font-weight: 600; ">Friday</span></p><p class="brz-fs-lg-1_07 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p>'
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
          horizontalAlign: undefined,
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                colorHex: "#475993",
                colorOpacity: 1,
                colorPalette: "",
                customSize: 26,
                hoverColorHex: "#475993",
                hoverColorOpacity: 0.8,
                hoverColorPalette: "",
                linkExternal:
                  "https://www.facebook.com/CalvaryBaptistChurchYankton/",
                linkExternalBlank: "on",
                linkType: "external",
                name: "facebook-square",
                padding: 7,
                type: "fa"
              }
            },
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                customSize: 26,
                linkExternal: "https://www.instagram.com/calvary_yankton/",
                linkExternalBlank: "on",
                linkType: "external",
                name: "instagram",
                padding: 7,
                type: "fa"
              }
            },
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                customSize: 26,
                linkExternal:
                  "https://www.youtube.com/channel/UCepa_vIqFOeD45nxFEx1Ykg",
                linkExternalBlank: "on",
                linkType: "external",
                name: "youtube",
                padding: 7,
                type: "fa"
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
                text: '<p class="brz-fs-lg-1_07 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-16_04 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-0"><span style="color: rgb(169, 130, 173); font-weight: 600; ">Sunday Services:&nbsp;</span><span style="color: rgb(0, 0, 0); font-weight: 600; ">Worship Service</span><span style="font-weight: 600; color: rgb(0, 0, 0); "><span>&nbsp;</span>–<span style="color: rgb(0, 0, 0); font-weight: 600; "> 9:00am &amp;&nbsp;</span></span><span style="color: rgb(0, 0, 0); font-weight: 600; ">10:30am</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span style="color: rgb(167, 130, 171); font-weight: 600; ">Wednesday Evenings:&nbsp;</span><span style="color: rgb(0, 0, 0); font-weight: 600; ">Awana</span><span style="font-weight: 600; color: rgb(0, 0, 0); "><span style="font-weight: 600; ">&nbsp;&amp; Rev 56 </span>–<span>&nbsp;</span></span><span style="color: rgb(0, 0, 0); font-weight: 600; ">6:30pm </span><span style="color: rgb(0, 0, 0); font-weight: 600; ">(Sept-May)&nbsp;</span></p><p class="brz-fs-lg-1_06 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-0"><span style="color: rgb(0, 0, 0); font-weight: 600; "><span style="font-weight: 600; color: rgb(0, 0, 0); ">Calvary Student Ministry</span><span style="font-weight: 600; color: rgb(0, 0, 0); ">&nbsp;– 6:30</span></span><span style="color: rgb(0, 0, 0); font-weight: 600; "><span style=""><span style="">pm</span> <span style="">(Sept-May /</span> <span style="">summer varies, see </span></span><a data-location="existing" data-button="false" data-detail="516769" data-category="link" target="_self" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22%2Fministries%2Fstudents%22%2C%22externalBlank%22%3A%22off%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span>Student Ministry</span></a><span style="color: rgb(0, 0, 0); font-weight: 600; ">)</span><br></span><span style="font-weight: 600; color: rgb(0, 0, 0); ">Adult Discipleship Group (Bible Study)<span style="font-weight: 600; color: rgb(0, 0, 0); "><span>&nbsp;</span>–<span>&nbsp;</span></span>6:30pm (Sept-May)</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p>'
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 600; color: rgb(0, 0, 0); ">Online Giving: &nbsp;</span></p>'
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
          horizontalAlign: undefined,
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                customSize: 26,
                linkExternal:
                  "https://calvary-baptist-yankton.churchcenter.com/giving",
                linkExternalBlank: "on",
                linkType: "external",
                name: "dollar-sign",
                padding: 7,
                type: "fa"
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 300; ">Calvary Baptist is a part of </span><a style="" data-location="external" data-detail="https://www.convergeheartland.org/" data-category="link" target="_blank" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fwww.convergeheartland.org%2F%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span>Converge Heartland</span></a><span style="font-weight: 300; "> and </span><a style="color: rgb(181, 145, 184); " data-location="external" data-detail="https://converge.org/" data-category="link" target="_blank" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fconverge.org%2F%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span style="color: rgb(181, 145, 184); ">Converge Worldwide</span></a><span style="font-weight: 300; ">.</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"></p>'
              }
            }
          ]
        }
      }
    ]
  }
};

// Voyage example 57 (Icon is missing)
const ex57: Data = {
  html: `<div class="text-content text-1 editable" data-id="8735954" data-category="text"><div><p style="text-align: left; font-weight: 700; font-size: 1.0013em; color: rgb(0, 0, 0); font-family: Lato, &quot;Lato Regular&quot;, sans-serif;">Calvary student ministry is for those in 7th-12th grade. We gather together on a weekly basis to study the Bible and to grow in relationship with God and one another.</p><p style="text-align: left; font-weight: 400; font-size: 1.0013em; color: rgb(0, 0, 0); font-family: Lato, &quot;Lato Regular&quot;, sans-serif;"><br></p><p style="text-align: left; font-weight: 400; font-size: 1.0013em; color: rgb(0, 0, 0); font-family: Lato, &quot;Lato Regular&quot;, sans-serif;">Students meets regularly on Sunday mornings at 9:00 a.m. to study God's Word.&nbsp;&nbsp;</p><p style="text-align: left; font-weight: 400; font-size: 1.0013em; color: rgb(0, 0, 0); font-family: Lato, &quot;Lato Regular&quot;, sans-serif;">Wednesday evenings during the school year, we meet from <span class="clovercustom" style="font-size: 16.0008px; font-style: normal; font-weight: 400; letter-spacing: normal; text-align: left;">6:30-8:00 p.m.&nbsp; Evenings </span>include fellowship, worship, a message, and "tribe" time.&nbsp;</p><p style="text-align: left; font-weight: 400; font-size: 1.0013em; color: rgb(0, 0, 0); font-family: Lato, &quot;Lato Regular&quot;, sans-serif;">Many special events take place throughout the year so be sure to watch our Facebook page and pick up a copy of the monthly student calendar in the church lobby <span class="clovercustom" style="font-size: 16.0008px; font-style: normal; letter-spacing: normal; text-align: left;">for details on our upcoming events</span>.&nbsp;&nbsp;</p><p style="text-align: left; font-weight: 600; font-size: 1.0013em;"><br></p><p style="text-align: left; font-weight: 600; color: rgb(49, 76, 92); font-size: 1.0013em;"><br></p><p style="text-align: left; font-weight: 600; color: rgb(49, 76, 92); font-size: 1.6896em;"><span data-socialicon="roundedfacebook"><span class="socialIconSymbol" style="color: rgb(71, 89, 147);" aria-hidden="true"><a href="https://www.facebook.com/groups/PursuitYankton/" data-location="external" data-detail="https://www.facebook.com/groups/PursuitYankton/" data-category="link" target="_blank" class="cloverlinks" style="color: rgb(71, 89, 147);"></a> </span><span class="sr-only">roundedfacebook</span></span><a href="https://www.instagram.com/asm_yankton/" data-location="external" data-detail="https://www.instagram.com/asm_yankton/" data-category="link" target="_blank" class="cloverlinks socialIconLink" style="font-size: 26.999807357788086px; font-style: normal; font-weight: 600; letter-spacing: normal; text-decoration: none; color: rgb(194, 47, 135);" data-button="false"><span data-socialicon="roundedinstagram"><span class="socialIconSymbol" style="font-size: 2em; font-style: normal; font-weight: 300;" aria-hidden="true"></span><span class="sr-only">roundedinstagram</span></span> </a></p><p style="text-align: left; font-weight: 600;"><br></p></div></div>`,
  entry: { ...entry, selector: '[data-id="8735954"]' },
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(0, 0, 0); font-weight: 700; ">Calvary student ministry is for those in 7th-12th grade. We gather together on a weekly basis to study the Bible and to grow in relationship with God and one another.</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(0, 0, 0); font-weight: 400; ">Students meets regularly on Sunday mornings at 9:00 a.m. to study God\'s Word.&nbsp;&nbsp;</span></p><p class="brz-fs-lg-16 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-0"><span style="color: rgb(0, 0, 0); font-weight: 400; ">Wednesday evenings during the school year, we meet from </span><span style="font-weight: 400; color: rgb(0, 0, 0); ">6:30-8:00 p.m.&nbsp; Evenings </span><span style="color: rgb(0, 0, 0); font-weight: 400; ">include fellowship, worship, a message, and "tribe" time.&nbsp;</span></p><p class="brz-fs-lg-16 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-0"><span style="color: rgb(0, 0, 0); font-weight: 400; ">Many special events take place throughout the year so be sure to watch our Facebook page and pick up a copy of the monthly student calendar in the church lobby </span><span style="color: rgb(0, 0, 0); font-weight: 400; ">for details on our upcoming events</span><span style="color: rgb(0, 0, 0); font-weight: 400; ">.&nbsp;&nbsp;</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p>'
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
          horizontalAlign: undefined,
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                colorHex: "#475993",
                colorOpacity: 1,
                colorPalette: "",
                customSize: 26,
                hoverColorHex: "#475993",
                hoverColorOpacity: 0.8,
                hoverColorPalette: "",
                name: "facebook-square",
                padding: 7,
                type: "fa",
                linkExternal: "https://www.facebook.com/groups/PursuitYankton/",
                linkExternalBlank: "on",
                linkType: "external"
              }
            },
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                customSize: 26,
                linkExternal: "https://www.instagram.com/asm_yankton/",
                linkExternalBlank: "on",
                linkType: "external",
                name: "instagram",
                padding: 7,
                type: "fa"
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p>'
              }
            }
          ]
        }
      }
    ]
  }
};

// Dusk example 58 (Text spacing missing)
const ex58: Data = {
  html: `<div class="text-content text-1 editable" data-id="24420966" data-category="text"><div><div id="contents" style="font-family: Ubuntu, &quot;Ubuntu Regular&quot;, sans-serif; font-weight: 400; text-align: left;"><qowt-section qowt-eid="E84" id="E84" named-flow="FLOW-2"><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E99" id="E99"><span is="qowt-word-run" qowt-teid="T9" class="clovercustom" style="font-size: 14pt;"><br></span>
</p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E100" id="E100"><span is="qowt-word-run" qowt-eid="E101" id="E101" class="clovercustom" style="font-size: 14pt;">1.  THE PLENARY (FULL - COMPLETE) VERBAL INSPIRATION OF THE SCRIPTURES - </span><span is="qowt-word-run" qowt-eid="E103" id="E103" class="clovercustom" style="font-size: 14pt;">That the scriptures of both the </span><span is="qowt-word-run" qowt-eid="E105" id="E105" class="clovercustom" style="font-size: 14pt;">Old  and</span><span is="qowt-word-run" qowt-eid="E107" id="E107" class="clovercustom" style="font-size: 14pt;"> New Testament were verbally inspired of God and inerrant in their original writings and are of supreme and f</span><span is="qowt-word-run" qowt-eid="E108" id="E108" class="clovercustom" style="font-size: 14pt;">inal authority of faith and lif</span><span is="qowt-word-run" qowt-eid="E109" id="E109" class="clovercustom" style="font-size: 14pt;">e.&nbsp;</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E100" id="E100"><span is="qowt-word-run" qowt-eid="E111" class="clovercustom" style="font-size: 14pt;"><br></span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E100" id="E100"><span is="qowt-word-run" qowt-eid="E111" id="E111" class="clovercustom" style="font-size: 14pt;">II Tim. 3:16-</span><span is="qowt-word-run" qowt-eid="E113" id="E113" class="clovercustom" style="font-size: 14pt;">17;  II</span><span is="qowt-word-run" qowt-eid="E115" id="E115" class="clovercustom" style="font-size: 14pt;"> Peter 1:21; Romans 15:4.</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E116" id="E116">
</p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E117" id="E117"><span is="qowt-word-run" qowt-teid="T11" class="clovercustom" style="font-size: 14pt;"><br></span>
</p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E118" id="E118"><span is="qowt-word-run" qowt-eid="E119" id="E119" class="clovercustom" style="font-size: 14pt;">2.  CREATION STATEMENT - We accept the Genesis account of creation and believe that man came by direct creation o</span><span is="qowt-word-run" qowt-eid="E120" id="E120" class="clovercustom" style="font-size: 14pt;">f God and not of evolution.</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E118" id="E118"><span is="qowt-word-run" qowt-eid="E121" class="clovercustom" style="font-size: 14pt;"><br></span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E118" id="E118"><span is="qowt-word-run" qowt-eid="E121" id="E121" class="clovercustom" style="font-size: 14pt;">Gen. 1</span><span is="qowt-word-run" qowt-eid="E122" id="E122" class="clovercustom" style="font-size: 14pt;"> &amp;</span><span is="qowt-word-run" qowt-eid="E123" id="E123" class="clovercustom" style="font-size: 14pt;"> 2; Col. 1:16-17; John 1:3.</span>
</p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E124" id="E124"><span is="qowt-word-run" qowt-teid="T12" class="clovercustom" style="font-size: 14pt;"><br></span>
</p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E126" id="E126"><span is="qowt-word-run" qowt-eid="E127" id="E127" class="clovercustom" style="font-size: 14pt;">3.  THE TRINITY - That Go</span><span is="qowt-word-run" qowt-eid="E128" id="E128" class="clovercustom" style="font-size: 14pt;">d is one God existing in three P</span><span is="qowt-word-run" qowt-eid="E129" id="E129" class="clovercustom" style="font-size: 14pt;">ersons; Father, Son and Holy Spirit; that these are co-eternal and co-equal as to nature, attributes, and perfections.&nbsp;</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E126" id="E126"><span is="qowt-word-run" qowt-eid="E129" class="clovercustom" style="font-size: 14pt;"><br></span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E126" id="E126"><span is="qowt-word-run" qowt-eid="E129" class="clovercustom" style="font-size: 14pt;"> Deut. 6:4; John 1:1-4; Matt. 28:19-20;</span><span is="qowt-word-run" qowt-eid="E130" id="E130" class="clovercustom" style="font-size: 14pt;"> </span><span is="qowt-word-run" qowt-eid="E131" id="E131" class="clovercustom" style="font-size: 14pt;">II Cor. 13:14.</span>
</p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E132" id="E132"><span is="qowt-word-run" qowt-teid="T13" class="clovercustom" style="font-size: 14pt;"><br></span>
</p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E133" id="E133"><span is="qowt-word-run" qowt-eid="E134" id="E134" class="clovercustom" style="font-size: 14pt;">4.  THE DEITY OF CHRIST - That Jesus Christ is the pre-existent Son of God, begotten of t</span><span is="qowt-word-run" qowt-eid="E135" id="E135" class="clovercustom" style="font-size: 14pt;">he Holy Spirit and born of the v</span><span is="qowt-word-run" qowt-eid="E136" id="E136" class="clovercustom" style="font-size: 14pt;">irgin Mary as true God and true </span><span is="qowt-word-run" qowt-eid="E139" id="E139" class="clovercustom" style="font-size: 14pt;">Man.</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E133" id="E133"><span is="qowt-word-run" qowt-eid="E140" class="clovercustom" style="font-size: 14pt;"><br></span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E133" id="E133"><span is="qowt-word-run" qowt-eid="E140" id="E140" class="clovercustom" style="font-size: 14pt;">  John 1:1-2; John 1:14</span><span is="qowt-word-run" qowt-eid="E142" id="E142" class="clovercustom" style="font-size: 14pt;">;  Matt.</span><span is="qowt-word-run" qowt-eid="E144" id="E144" class="clovercustom" style="font-size: 14pt;"> 1:18-23; Luke 1:35; John 20:30-31.</span>
</p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E145" id="E145"><span is="qowt-word-run" qowt-teid="T14" class="clovercustom" style="font-size: 14pt;"><br></span>
</p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E146" id="E146"><span is="qowt-word-run" qowt-eid="E147" id="E147" class="clovercustom" style="font-size: 14pt;">5.  THE CLEANSING BLOOD OF </span><span is="qowt-word-run" qowt-eid="E149" id="E149" class="clovercustom" style="font-size: 14pt;">CHRIST </span><span is="qowt-word-run" qowt-eid="E150" id="E150" class="clovercustom" style="font-size: 14pt;"> -</span><span is="qowt-word-run" qowt-eid="E152" id="E152" class="clovercustom" style="font-size: 14pt;"> That the Lord Jesus Christ died for our sins according to the Scriptures as a voluntary representative and substitutiona</span><span is="qowt-word-run" qowt-eid="E153" id="E153" class="clovercustom" style="font-size: 14pt;">ry sacrifice, and that all who </span><span is="qowt-word-run" qowt-eid="E154" id="E154" class="clovercustom" style="font-size: 14pt;">receive </span><span is="qowt-word-run" qowt-eid="E155" id="E155" class="clovercustom" style="font-size: 14pt;">Him as personal Savior and lord;</span><span is="qowt-word-run" qowt-eid="E156" id="E156" class="clovercustom" style="font-size: 14pt;"> believe (trust or commit) in Him are justified on the ground of His shed blood.</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E146" id="E146"><span is="qowt-word-run" qowt-eid="E157" class="clovercustom" style="font-size: 14pt;"><br></span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E146" id="E146"><span is="qowt-word-run" qowt-eid="E157" id="E157" class="clovercustom" style="font-size: 14pt;">Rom. 3:23-</span><span is="qowt-word-run" qowt-eid="E159" id="E159" class="clovercustom" style="font-size: 14pt;">25;  </span><span is="qowt-word-run" qowt-eid="E160" id="E160" class="clovercustom" style="font-size: 14pt;">Rom.</span><span is="qowt-word-run" qowt-eid="E162" id="E162" class="clovercustom" style="font-size: 14pt;"> 5:6-8; I Cor. 15:3-4; II Cor. 5:21; </span><span is="qowt-word-run" qowt-eid="E163" id="E163" class="clovercustom" style="font-size: 14pt;">Hebrews 9:11-14 Hebrews 10:4 &amp; 11-14;</span><span is="qowt-word-run" qowt-eid="E164" id="E164" class="clovercustom" style="font-size: 14pt;"> I Peter 1:18-19</span><span is="qowt-word-run" qowt-eid="E165" id="E165" class="clovercustom" style="font-size: 14pt;">;</span><span is="qowt-word-run" qowt-eid="E166" id="E166" class="clovercustom" style="font-size: 14pt;">  I John 2:2</span><span is="qowt-word-run" qowt-eid="E167" id="E167" class="clovercustom" style="font-size: 14pt;">;</span><span is="qowt-word-run" qowt-eid="E168" id="E168" class="clovercustom" style="font-size: 14pt;"> </span><span is="qowt-word-run" qowt-eid="E169" id="E169" class="clovercustom" style="font-size: 14pt;"> </span><span is="qowt-word-run" qowt-eid="E170" id="E170" class="clovercustom" style="font-size: 14pt;">I John 4:10</span><span is="qowt-word-run" qowt-eid="E171" id="E171" class="clovercustom" style="font-size: 14pt;">.</span>
</p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E172" id="E172"><span is="qowt-word-run" qowt-teid="T15" class="clovercustom" style="font-size: 14pt;"><br></span>
</p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E173" id="E173"><span is="qowt-word-run" qowt-eid="E174" id="E174" class="clovercustom" style="font-size: 14pt;">6.  THE RESURRECTION OF CHRIST - In the resurrection of the</span><span is="qowt-word-run" qowt-eid="E175" id="E175" class="clovercustom" style="font-size: 14pt;"> </span><span is="qowt-word-run" qowt-eid="E176" id="E176" class="clovercustom" style="font-size: 14pt;">crucified body of our Lord Jesus, in His ascension and exaltation into Heaven, and His present Mediatorial High Priestly</span><span is="qowt-word-run" qowt-eid="E177" id="E177" class="clovercustom" style="font-size: 14pt;"> office.</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E173" id="E173"><span is="qowt-word-run" qowt-eid="E177" class="clovercustom" style="font-size: 14pt;"><br></span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E173" id="E173"><span is="qowt-word-run" qowt-eid="E177" class="clovercustom" style="font-size: 14pt;"> John 20:1-31; Acts 2:31</span><span is="qowt-word-run" qowt-eid="E178" id="E178" class="clovercustom" style="font-size: 14pt;">-36; Phil. 2:5-11; I Cor. 15:2-23; Eph. 1:19-21; Heb. 9:24; I John 2:1-2.</span>
</p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E179" id="E179"><span is="qowt-word-run" qowt-teid="T16" class="clovercustom" style="font-size: 14pt;"><br></span>
</p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E180" id="E180" named-flow="FLOW-3"><span is="qowt-word-run" qowt-eid="E181" id="E181" class="clovercustom" style="font-size: 14pt;">7.  THE SECOND COMING OF CHRIST - That the Blessed Hope</span><span is="qowt-word-run" qowt-eid="E182" id="E182" class="clovercustom" style="font-size: 14pt;"> </span><span is="qowt-word-run" qowt-eid="E184" id="E184" class="clovercustom" style="font-size: 14pt;">of </span><span is="qowt-word-run" qowt-eid="E185" id="E185" class="clovercustom" style="font-size: 14pt;"> </span><span is="qowt-word-run" qowt-eid="E186" id="E186" class="clovercustom" style="font-size: 14pt;">believers</span><span is="qowt-word-run" qowt-eid="E188" id="E188" class="clovercustom" style="font-size: 14pt;"> is the (pre-tribulation) appearing of C</span><span is="qowt-word-run" qowt-eid="E189" id="E189" class="clovercustom" style="font-size: 14pt;">hrist in the air for His church</span><span is="qowt-word-run" qowt-eid="E190" id="E190" named-flow="FLOW-4" class="clovercustom" style="font-size: 14pt;"> (which is imminent) to be followed by His personal visible return to the earth to set up His millennial</span></p></qowt-section><qowt-page named-flow="FLOW-1"><div id="contents"><qowt-section named-flow="FLOW-2" break-before="" qowt-eid="E84" indexed-flow="SI18"><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" named-flow="FLOW-3" qowt-eid="E180"><span is="qowt-word-run" named-flow="FLOW-4" qowt-eid="E190" class="clovercustom" style="font-size: 14pt;">kingdom.&nbsp;</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" named-flow="FLOW-3" qowt-eid="E180"><span is="qowt-word-run" named-flow="FLOW-4" qowt-eid="E190" class="clovercustom" style="font-size: 14pt;"><br></span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" named-flow="FLOW-3" qowt-eid="E180"><span is="qowt-word-run" named-flow="FLOW-4" qowt-eid="E190" class="clovercustom" style="font-size: 14pt;"> Acts 1:11; I Thes. 4:13-18; John 14: 1-3; Rev. 20:4-6</span><span is="qowt-word-run" qowt-eid="E191" id="E191" class="clovercustom" style="font-size: 14pt;">.</span>
</p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E192" id="E192"><span is="qowt-word-run" qowt-teid="T17" class="clovercustom" style="font-size: 14pt;"><br></span>
</p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E193" id="E193"><span is="qowt-word-run" qowt-eid="E194" id="E194" class="clovercustom" style="font-size: 14pt;">8.  THE HOLY SPIRIT - That the Holy Spirit is </span><span is="qowt-word-run" qowt-eid="E195" id="E195" class="clovercustom" style="font-size: 14pt;">the third P</span><span is="qowt-word-run" qowt-eid="E196" id="E196" class="clovercustom" style="font-size: 14pt;">erson of the Godhead, and </span><span is="qowt-word-run" qowt-eid="E197" id="E197" class="clovercustom" style="font-size: 14pt;">that He convicts the world of sin, righteousness, judgment; that He regenerates, indwells, infills, enlightens, guides and comforts the believer.</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E193" id="E193"><span is="qowt-word-run" qowt-eid="E197" class="clovercustom" style="font-size: 14pt;"><br></span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E193" id="E193"><span is="qowt-word-run" qowt-eid="E197" class="clovercustom" style="font-size: 14pt;"> John 14:16-17; John 15:26-27; John 16:7-15; I Cor. 3:16; I Cor. 12:13; Eph. 1:13-14; Eph. 4:30.</span>
</p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E199" id="E199"><span is="qowt-word-run" qowt-teid="T20" class="clovercustom" style="font-size: 14pt;"><br></span>
</p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E200" id="E200"><span is="qowt-word-run" qowt-eid="E201" id="E201" class="clovercustom" style="font-size: 14pt;">9.  THE TOTAL DEPRAVITY OF MAN - That man was created in the</span>
</p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E202" id="E202"><span is="qowt-word-run" qowt-eid="E203" id="E203" class="clovercustom" style="font-size: 14pt;">image of God; that he sinned and not only incurre</span><span is="qowt-word-run" qowt-eid="E204" id="E204" class="clovercustom" style="font-size: 14pt;">d physical death, but also </span><span is="qowt-word-run" qowt-eid="E205" id="E205" class="clovercustom" style="font-size: 14pt;">spiritual death which is separation from God; that all human beings are born with a sinful nature and are sinners in thought, word and deed.&nbsp;</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E202" id="E202"><span is="qowt-word-run" qowt-eid="E208" class="clovercustom" style="font-size: 14pt;"><br></span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E202" id="E202"><span is="qowt-word-run" qowt-eid="E208" id="E208" class="clovercustom" style="font-size: 14pt;">Rom.</span><span is="qowt-word-run" qowt-eid="E209" id="E209" class="clovercustom" style="font-size: 14pt;"> 3:9-19; Rom. 3:23; Jer. 17:9; </span><span is="qowt-word-run" qowt-eid="E210" id="E210" class="clovercustom" style="font-size: 14pt;">Rom. 5:12.</span>
</p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E211" id="E211"><span is="qowt-word-run" qowt-teid="T21" class="clovercustom" style="font-size: 14pt;"><br></span>
</p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E212" id="E212"><span is="qowt-word-run" qowt-eid="E213" id="E213" class="clovercustom" style="font-size: 14pt;">10.  JUSTIFICATION BY FAITH - </span><span is="qowt-word-run" qowt-eid="E214" id="E214" class="clovercustom" style="font-size: 14pt;">We believe that salvation is a gift of God received by grace through faith in the Lord Jesus Christ alone.   </span><span is="qowt-word-run" qowt-eid="E215" id="E215" class="clovercustom" style="font-size: 14pt;">That the result of the heart acceptance of Jesu</span><span is="qowt-word-run" qowt-eid="E216" id="E216" class="clovercustom" style="font-size: 14pt;">s Christ as personal Savior is </span><span is="qowt-word-run" qowt-eid="E217" id="E217" class="clovercustom" style="font-size: 14pt;">justification whereby pardon is secured and we are brought into a state of peace and favor with God, declared righteous absolutely apart from any personal merit.</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E212" id="E212"><span is="qowt-word-run" qowt-eid="E217" class="clovercustom" style="font-size: 14pt;"><br></span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E212" id="E212"><span is="qowt-word-run" qowt-eid="E217" class="clovercustom" style="font-size: 14pt;"> Eph. 2:8-9;</span>&nbsp;<span is="qowt-word-run" qowt-eid="E221" id="E221" class="clovercustom" style="letter-spacing: 0em; font-size: 14pt;">Rom.</span><span is="qowt-word-run" qowt-eid="E222" id="E222" class="clovercustom" style="letter-spacing: 0em; font-size: 14pt;"> 4:4-5; Rom. 5:1; John 1:12; John </span><span is="qowt-word-run" qowt-eid="E225" id="E225" class="clovercustom" style="letter-spacing: 0em; font-size: 14pt;">5:24</span><span is="qowt-word-run" qowt-eid="E226" id="E226" class="clovercustom" style="letter-spacing: 0em; font-size: 14pt;">; Rom. </span><span is="qowt-word-run" qowt-eid="E229" id="E229" class="clovercustom" style="letter-spacing: 0em; font-size: 14pt;">3:24</span><span is="qowt-word-run" qowt-eid="E230" id="E230" class="clovercustom" style="letter-spacing: 0em; font-size: 14pt;">-28.</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E231" id="E231"><span is="qowt-word-run" qowt-teid="T22" class="clovercustom" style="font-size: 14pt;"><br></span>
</p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E232" id="E232"><span is="qowt-word-run" qowt-eid="E233" id="E233" class="clovercustom" style="font-size: 14pt;">11.  ETERNAL </span><span is="qowt-word-run" qowt-eid="E235" id="E235" class="clovercustom" style="font-size: 14pt;">SALVATION  -</span><span is="qowt-word-run" qowt-eid="E237" id="E237" class="clovercustom" style="font-size: 14pt;"> That each person who is born again by the Holy Spirit is </span><span is="qowt-word-run" qowt-eid="E238" id="E238" class="clovercustom" style="font-size: 14pt;">saved</span><span is="qowt-word-run" qowt-eid="E239" id="E239" class="clovercustom" style="font-size: 14pt;"> by the power of God and </span><span is="qowt-word-run" qowt-eid="E240" id="E240" class="clovercustom" style="font-size: 14pt;">kept </span><span is="qowt-word-run" qowt-eid="E241" id="E241" class="clovercustom" style="font-size: 14pt;">by the po</span><span is="qowt-word-run" qowt-eid="E242" id="E242" class="clovercustom" style="font-size: 14pt;">wer of God.&nbsp;</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E232" id="E232"><span is="qowt-word-run" qowt-eid="E242" class="clovercustom" style="font-size: 14pt;"><br></span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E232" id="E232"><span is="qowt-word-run" qowt-eid="E242" class="clovercustom" style="font-size: 14pt;"> John 1:12; John 3:14</span><span is="qowt-word-run" qowt-eid="E243" id="E243" class="clovercustom" style="font-size: 14pt;">-18; John 5:24; John 10:28-29; Rom. 8:1; Rom. </span><span is="qowt-word-run" qowt-eid="E246" id="E246" class="clovercustom" style="font-size: 14pt;">8:35</span><span is="qowt-word-run" qowt-eid="E247" id="E247" class="clovercustom" style="font-size: 14pt;">-39; Heb. 10:14; Eph. 1:13-14; Phil. 1:6; Jude 1 and 24; I Peter 1:5.</span>
</p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E248" id="E248"><span is="qowt-word-run" qowt-teid="T23" class="clovercustom" style="font-size: 14pt;"><br></span>
</p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E249" id="E249"><span is="qowt-word-run" qowt-eid="E250" id="E250" class="clovercustom" style="font-size: 14pt;">12.  </span><span is="qowt-word-run" qowt-eid="E251" id="E251" class="clovercustom" style="font-size: 14pt;"> BELIEVERS BEING IN THE WORLD NOT OF THE WORLD, SEPARATED UNTO GOD</span><span is="qowt-word-run" qowt-eid="E252" id="E252" class="clovercustom" style="font-size: 14pt;"> - That every saved person is called into a life of sepa</span><span is="qowt-word-run" qowt-eid="E253" id="E253" class="clovercustom" style="font-size: 14pt;">ration from all </span><span is="qowt-word-run" qowt-eid="E254" id="E254" class="clovercustom" style="font-size: 14pt;">sinful pr</span><span is="qowt-word-run" qowt-eid="E255" id="E255" class="clovercustom" style="font-size: 14pt;">actices</span><span is="qowt-word-run" qowt-eid="E256" id="E256" class="clovercustom" style="font-size: 14pt;">.</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E249" id="E249"><span is="qowt-word-run" qowt-eid="E259" class="clovercustom" style="font-size: 14pt;"><br></span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E249" id="E249"><span is="qowt-word-run" qowt-eid="E259" id="E259" class="clovercustom" style="font-size: 14pt;">Rom.</span><span is="qowt-word-run" qowt-eid="E260" id="E260" class="clovercustom" style="font-size: 14pt;"> 12:1-2; II Cor. 6:14</span><span is="qowt-word-run" qowt-eid="E261" id="E261" class="clovercustom" style="font-size: 14pt;">; Heb. 12:1-3;</span>&nbsp;<span is="qowt-word-run" qowt-eid="E263" id="E263" class="clovercustom" style="letter-spacing: 0em; font-size: 14pt;">James</span><span is="qowt-word-run" qowt-eid="E264" id="E264" class="clovercustom" style="letter-spacing: 0em; font-size: 14pt;"> 4:4; I John 2:16.</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E265" id="E265"><span is="qowt-word-run" qowt-teid="T24" class="clovercustom" style="font-size: 14pt;"><br></span>
</p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E266" id="E266"><span is="qowt-word-run" qowt-eid="E267" id="E267" class="clovercustom" style="font-size: 14pt;">13.  THE RESURRECTION OF THE BODY - That in the bodily resurrection of both the just and unjust, but at differe</span><span is="qowt-word-run" qowt-eid="E268" id="E268" class="clovercustom" style="font-size: 14pt;">nt times; the just are raised </span><span is="qowt-word-run" qowt-eid="E269" id="E269" class="clovercustom" style="font-size: 14pt;">to etern</span><span is="qowt-word-run" qowt-eid="E270" id="E270" class="clovercustom" style="font-size: 14pt;">al blessedness and the unjust </span><span is="qowt-word-run" qowt-eid="E271" id="E271" class="clovercustom" style="font-size: 14pt;">to eternal conscious punishment.</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E266" id="E266"><span is="qowt-word-run" qowt-eid="E272" class="clovercustom" style="font-size: 14pt;"><br></span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E266" id="E266"><span is="qowt-word-run" qowt-eid="E272" id="E272" class="clovercustom" style="font-size: 14pt;">Dan 12:2</span><span is="qowt-word-run" qowt-eid="E274" id="E274" class="clovercustom" style="font-size: 14pt;">;  John</span><span is="qowt-word-run" qowt-eid="E276" id="E276" class="clovercustom" style="font-size: 14pt;"> 5:28-29; </span><span is="qowt-word-run" qowt-eid="E277" id="E277" class="clovercustom" style="font-size: 14pt;">I Thes. </span><span is="qowt-word-run" qowt-eid="E280" id="E280" class="clovercustom" style="font-size: 14pt;">4:13</span><span is="qowt-word-run" qowt-eid="E281" id="E281" class="clovercustom" style="font-size: 14pt;">-18; I Cor. 15:12-20</span><span is="qowt-word-run" qowt-eid="E282" id="E282" class="clovercustom" style="font-size: 14pt;">; Rev. 20:4-6; Rev. 20:11-15; </span>
</p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E283" id="E283"><span is="qowt-word-run" qowt-teid="T25" class="clovercustom" style="font-size: 14pt;"><br></span>
</p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E284" id="E284"><span is="qowt-word-run" qowt-eid="E285" id="E285" class="clovercustom" style="font-size: 14pt;">14.  THE CHURCH - That the</span><span is="qowt-word-run" qowt-eid="E286" id="E286" class="clovercustom" style="font-size: 14pt;"> universal</span><span is="qowt-word-run" qowt-eid="E287" id="E287" class="clovercustom" style="font-size: 14pt;"> </span><span is="qowt-word-run" qowt-eid="E289" id="E289" class="clovercustom" style="font-size: 14pt;">church, </span><span is="qowt-word-run" qowt-eid="E290" id="E290" class="clovercustom" style="font-size: 14pt;"> as</span><span is="qowt-word-run" qowt-eid="E292" id="E292" class="clovercustom" style="font-size: 14pt;"> an </span><span is="qowt-word-run" qowt-eid="E293" id="E293" class="clovercustom" style="font-size: 14pt;">organism, is the body and bride of Christ and includes the whole company of believers from Pentecost to the Rapture</span><span is="qowt-word-run" qowt-eid="E294" id="E294" class="clovercustom" style="font-size: 14pt;"> and is known only to God.  This</span><span is="qowt-word-run" qowt-eid="E295" id="E295" class="clovercustom" style="font-size: 14pt;"> </span><span is="qowt-word-run" qowt-eid="E296" id="E296" class="clovercustom" style="font-size: 14pt;">local church, as an</span><span is="qowt-word-run" qowt-eid="E297" id="E297" class="clovercustom" style="font-size: 14pt;"> organization is a </span>
</p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E298" id="E298" named-flow="FLOW-5"><span is="qowt-word-run" qowt-eid="E299" id="E299" named-flow="FLOW-6" class="clovercustom" style="font-size: 14pt;">company of believers baptized in the name of the Triune God and observing the form</span></p></qowt-section></div></qowt-page></div></div></div>`,
  entry: { ...entry, selector: '[data-id="24420966"]' },
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
                text: '<p id="contents" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><qowt-section qowt-eid="E84" id="E84" named-flow="FLOW-2"></qowt-section></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E99" id="E99" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-teid="T9" style=""><br></span> </p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E100" id="E100" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E101" id="E101" style="">1.  THE PLENARY (FULL - COMPLETE) VERBAL INSPIRATION OF THE SCRIPTURES - </span><span is="qowt-word-run" qowt-eid="E103" id="E103" style="">That the scriptures of both the </span><span is="qowt-word-run" qowt-eid="E105" id="E105" style="">Old  and</span><span is="qowt-word-run" qowt-eid="E107" id="E107" style=""> New Testament were verbally inspired of God and inerrant in their original writings and are of supreme and f</span><span is="qowt-word-run" qowt-eid="E108" id="E108" style="">inal authority of faith and lif</span><span is="qowt-word-run" qowt-eid="E109" id="E109" style="">e.&nbsp;</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E100" id="E100" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E111" style=""><br></span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E100" id="E100" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E111" id="E111" style="">II Tim. 3:16-</span><span is="qowt-word-run" qowt-eid="E113" id="E113" style="">17;  II</span><span is="qowt-word-run" qowt-eid="E115" id="E115" style=""> Peter 1:21; Romans 15:4.</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E117" id="E117" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-teid="T11" style=""><br></span> </p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E118" id="E118" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E119" id="E119" style="">2.  CREATION STATEMENT - We accept the Genesis account of creation and believe that man came by direct creation o</span><span is="qowt-word-run" qowt-eid="E120" id="E120" style="">f God and not of evolution.</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E118" id="E118" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E121" style=""><br></span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E118" id="E118" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E121" id="E121" style="">Gen. 1</span><span is="qowt-word-run" qowt-eid="E122" id="E122" style=""> &amp;</span><span is="qowt-word-run" qowt-eid="E123" id="E123" style=""> 2; Col. 1:16-17; John 1:3.</span> </p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E124" id="E124" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-teid="T12" style=""><br></span> </p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E126" id="E126" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E127" id="E127" style="">3.  THE TRINITY - That Go</span><span is="qowt-word-run" qowt-eid="E128" id="E128" style="">d is one God existing in three P</span><span is="qowt-word-run" qowt-eid="E129" id="E129" style="">ersons; Father, Son and Holy Spirit; that these are co-eternal and co-equal as to nature, attributes, and perfections.&nbsp;</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E126" id="E126" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E129" style=""><br></span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E126" id="E126" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E129" style=""> Deut. 6:4; John 1:1-4; Matt. 28:19-20;</span><span is="qowt-word-run" qowt-eid="E130" id="E130" style=""> </span><span is="qowt-word-run" qowt-eid="E131" id="E131" style="">II Cor. 13:14.</span> </p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E132" id="E132" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-teid="T13" style=""><br></span> </p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E133" id="E133" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E134" id="E134" style="">4.  THE DEITY OF CHRIST - That Jesus Christ is the pre-existent Son of God, begotten of t</span><span is="qowt-word-run" qowt-eid="E135" id="E135" style="">he Holy Spirit and born of the v</span><span is="qowt-word-run" qowt-eid="E136" id="E136" style="">irgin Mary as true God and true </span><span is="qowt-word-run" qowt-eid="E139" id="E139" style="">Man.</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E133" id="E133" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E140" style=""><br></span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E133" id="E133" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E140" id="E140" style=""> John 1:1-2; John 1:14</span><span is="qowt-word-run" qowt-eid="E142" id="E142" style="">;  Matt.</span><span is="qowt-word-run" qowt-eid="E144" id="E144" style=""> 1:18-23; Luke 1:35; John 20:30-31.</span> </p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E145" id="E145" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-teid="T14" style=""><br></span> </p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E146" id="E146" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E147" id="E147" style="">5.  THE CLEANSING BLOOD OF </span><span is="qowt-word-run" qowt-eid="E149" id="E149" style="">CHRIST </span><span is="qowt-word-run" qowt-eid="E150" id="E150" style=""> -</span><span is="qowt-word-run" qowt-eid="E152" id="E152" style=""> That the Lord Jesus Christ died for our sins according to the Scriptures as a voluntary representative and substitutiona</span><span is="qowt-word-run" qowt-eid="E153" id="E153" style="">ry sacrifice, and that all who </span><span is="qowt-word-run" qowt-eid="E154" id="E154" style="">receive </span><span is="qowt-word-run" qowt-eid="E155" id="E155" style="">Him as personal Savior and lord;</span><span is="qowt-word-run" qowt-eid="E156" id="E156" style=""> believe (trust or commit) in Him are justified on the ground of His shed blood.</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E146" id="E146" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E157" style=""><br></span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E146" id="E146" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E157" id="E157" style="">Rom. 3:23-</span><span is="qowt-word-run" qowt-eid="E159" id="E159" style="">25;  </span><span is="qowt-word-run" qowt-eid="E160" id="E160" style="">Rom.</span><span is="qowt-word-run" qowt-eid="E162" id="E162" style=""> 5:6-8; I Cor. 15:3-4; II Cor. 5:21; </span><span is="qowt-word-run" qowt-eid="E163" id="E163" style="">Hebrews 9:11-14 Hebrews 10:4 &amp; 11-14;</span><span is="qowt-word-run" qowt-eid="E164" id="E164" style=""> I Peter 1:18-19</span><span is="qowt-word-run" qowt-eid="E165" id="E165" style="">;</span><span is="qowt-word-run" qowt-eid="E166" id="E166" style=""> I John 2:2</span><span is="qowt-word-run" qowt-eid="E167" id="E167" style="">;</span><span is="qowt-word-run" qowt-eid="E168" id="E168" style=""> </span><span is="qowt-word-run" qowt-eid="E169" id="E169" style=""> </span><span is="qowt-word-run" qowt-eid="E170" id="E170" style="">I John 4:10</span><span is="qowt-word-run" qowt-eid="E171" id="E171" style="">.</span> </p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E172" id="E172" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-teid="T15" style=""><br></span> </p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E173" id="E173" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E174" id="E174" style="">6.  THE RESURRECTION OF CHRIST - In the resurrection of the</span><span is="qowt-word-run" qowt-eid="E175" id="E175" style=""> </span><span is="qowt-word-run" qowt-eid="E176" id="E176" style="">crucified body of our Lord Jesus, in His ascension and exaltation into Heaven, and His present Mediatorial High Priestly</span><span is="qowt-word-run" qowt-eid="E177" id="E177" style=""> office.</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E173" id="E173" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E177" style=""><br></span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E173" id="E173" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E177" style=""> John 20:1-31; Acts 2:31</span><span is="qowt-word-run" qowt-eid="E178" id="E178" style="">-36; Phil. 2:5-11; I Cor. 15:2-23; Eph. 1:19-21; Heb. 9:24; I John 2:1-2.</span> </p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E179" id="E179" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-teid="T16" style=""><br></span> </p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E180" id="E180" named-flow="FLOW-3" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E181" id="E181" style="">7.  THE SECOND COMING OF CHRIST - That the Blessed Hope</span><span is="qowt-word-run" qowt-eid="E182" id="E182" style=""> </span><span is="qowt-word-run" qowt-eid="E184" id="E184" style="">of </span><span is="qowt-word-run" qowt-eid="E185" id="E185" style=""> </span><span is="qowt-word-run" qowt-eid="E186" id="E186" style="">believers</span><span is="qowt-word-run" qowt-eid="E188" id="E188" style=""> is the (pre-tribulation) appearing of C</span><span is="qowt-word-run" qowt-eid="E189" id="E189" style="">hrist in the air for His church</span><span is="qowt-word-run" qowt-eid="E190" id="E190" named-flow="FLOW-4" style=""> (which is imminent) to be followed by His personal visible return to the earth to set up His millennial</span></p><qowt-page named-flow="FLOW-1"><p id="contents" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><qowt-section named-flow="FLOW-2" break-before="" qowt-eid="E84" indexed-flow="SI18"></qowt-section></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" named-flow="FLOW-3" qowt-eid="E180" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" named-flow="FLOW-4" qowt-eid="E190" style="">kingdom.&nbsp;</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" named-flow="FLOW-3" qowt-eid="E180" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" named-flow="FLOW-4" qowt-eid="E190" style=""><br></span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" named-flow="FLOW-3" qowt-eid="E180" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" named-flow="FLOW-4" qowt-eid="E190" style=""> Acts 1:11; I Thes. 4:13-18; John 14: 1-3; Rev. 20:4-6</span><span is="qowt-word-run" qowt-eid="E191" id="E191" style="">.</span> </p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E192" id="E192" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-teid="T17" style=""><br></span> </p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E193" id="E193" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E194" id="E194" style="">8.  THE HOLY SPIRIT - That the Holy Spirit is </span><span is="qowt-word-run" qowt-eid="E195" id="E195" style="">the third P</span><span is="qowt-word-run" qowt-eid="E196" id="E196" style="">erson of the Godhead, and </span><span is="qowt-word-run" qowt-eid="E197" id="E197" style="">that He convicts the world of sin, righteousness, judgment; that He regenerates, indwells, infills, enlightens, guides and comforts the believer.</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E193" id="E193" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E197" style=""><br></span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E193" id="E193" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E197" style=""> John 14:16-17; John 15:26-27; John 16:7-15; I Cor. 3:16; I Cor. 12:13; Eph. 1:13-14; Eph. 4:30.</span> </p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E199" id="E199" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-teid="T20" style=""><br></span> </p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E200" id="E200" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E201" id="E201" style="">9.  THE TOTAL DEPRAVITY OF MAN - That man was created in the</span> </p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E202" id="E202" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E203" id="E203" style="">image of God; that he sinned and not only incurre</span><span is="qowt-word-run" qowt-eid="E204" id="E204" style="">d physical death, but also </span><span is="qowt-word-run" qowt-eid="E205" id="E205" style="">spiritual death which is separation from God; that all human beings are born with a sinful nature and are sinners in thought, word and deed.&nbsp;</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E202" id="E202" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E208" style=""><br></span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E202" id="E202" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E208" id="E208" style="">Rom.</span><span is="qowt-word-run" qowt-eid="E209" id="E209" style=""> 3:9-19; Rom. 3:23; Jer. 17:9; </span><span is="qowt-word-run" qowt-eid="E210" id="E210" style="">Rom. 5:12.</span> </p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E211" id="E211" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-teid="T21" style=""><br></span> </p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E212" id="E212" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E213" id="E213" style="">10.  JUSTIFICATION BY FAITH - </span><span is="qowt-word-run" qowt-eid="E214" id="E214" style="">We believe that salvation is a gift of God received by grace through faith in the Lord Jesus Christ alone.   </span><span is="qowt-word-run" qowt-eid="E215" id="E215" style="">That the result of the heart acceptance of Jesu</span><span is="qowt-word-run" qowt-eid="E216" id="E216" style="">s Christ as personal Savior is </span><span is="qowt-word-run" qowt-eid="E217" id="E217" style="">justification whereby pardon is secured and we are brought into a state of peace and favor with God, declared righteous absolutely apart from any personal merit.</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E212" id="E212" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E217" style=""><br></span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E212" id="E212" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E217" style=""> Eph. 2:8-9;</span>&nbsp;<span is="qowt-word-run" qowt-eid="E221" id="E221" style="">Rom.</span><span is="qowt-word-run" qowt-eid="E222" id="E222" style=""> 4:4-5; Rom. 5:1; John 1:12; John </span><span is="qowt-word-run" qowt-eid="E225" id="E225" style="">5:24</span><span is="qowt-word-run" qowt-eid="E226" id="E226" style="">; Rom. </span><span is="qowt-word-run" qowt-eid="E229" id="E229" style="">3:24</span><span is="qowt-word-run" qowt-eid="E230" id="E230" style="">-28.</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E231" id="E231" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-teid="T22" style=""><br></span> </p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E232" id="E232" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E233" id="E233" style="">11.  ETERNAL </span><span is="qowt-word-run" qowt-eid="E235" id="E235" style="">SALVATION  -</span><span is="qowt-word-run" qowt-eid="E237" id="E237" style=""> That each person who is born again by the Holy Spirit is </span><span is="qowt-word-run" qowt-eid="E238" id="E238" style="">saved</span><span is="qowt-word-run" qowt-eid="E239" id="E239" style=""> by the power of God and </span><span is="qowt-word-run" qowt-eid="E240" id="E240" style="">kept </span><span is="qowt-word-run" qowt-eid="E241" id="E241" style="">by the po</span><span is="qowt-word-run" qowt-eid="E242" id="E242" style="">wer of God.&nbsp;</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E232" id="E232" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E242" style=""><br></span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E232" id="E232" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E242" style=""> John 1:12; John 3:14</span><span is="qowt-word-run" qowt-eid="E243" id="E243" style="">-18; John 5:24; John 10:28-29; Rom. 8:1; Rom. </span><span is="qowt-word-run" qowt-eid="E246" id="E246" style="">8:35</span><span is="qowt-word-run" qowt-eid="E247" id="E247" style="">-39; Heb. 10:14; Eph. 1:13-14; Phil. 1:6; Jude 1 and 24; I Peter 1:5.</span> </p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E248" id="E248" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-teid="T23" style=""><br></span> </p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E249" id="E249" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E250" id="E250" style="">12.  </span><span is="qowt-word-run" qowt-eid="E251" id="E251" style=""> BELIEVERS BEING IN THE WORLD NOT OF THE WORLD, SEPARATED UNTO GOD</span><span is="qowt-word-run" qowt-eid="E252" id="E252" style=""> - That every saved person is called into a life of sepa</span><span is="qowt-word-run" qowt-eid="E253" id="E253" style="">ration from all </span><span is="qowt-word-run" qowt-eid="E254" id="E254" style="">sinful pr</span><span is="qowt-word-run" qowt-eid="E255" id="E255" style="">actices</span><span is="qowt-word-run" qowt-eid="E256" id="E256" style="">.</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E249" id="E249" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E259" style=""><br></span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E249" id="E249" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E259" id="E259" style="">Rom.</span><span is="qowt-word-run" qowt-eid="E260" id="E260" style=""> 12:1-2; II Cor. 6:14</span><span is="qowt-word-run" qowt-eid="E261" id="E261" style="">; Heb. 12:1-3;</span>&nbsp;<span is="qowt-word-run" qowt-eid="E263" id="E263" style="">James</span><span is="qowt-word-run" qowt-eid="E264" id="E264" style=""> 4:4; I John 2:16.</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E265" id="E265" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-teid="T24" style=""><br></span> </p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E266" id="E266" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E267" id="E267" style="">13.  THE RESURRECTION OF THE BODY - That in the bodily resurrection of both the just and unjust, but at differe</span><span is="qowt-word-run" qowt-eid="E268" id="E268" style="">nt times; the just are raised </span><span is="qowt-word-run" qowt-eid="E269" id="E269" style="">to etern</span><span is="qowt-word-run" qowt-eid="E270" id="E270" style="">al blessedness and the unjust </span><span is="qowt-word-run" qowt-eid="E271" id="E271" style="">to eternal conscious punishment.</span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E266" id="E266" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E272" style=""><br></span></p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E266" id="E266" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E272" id="E272" style="">Dan 12:2</span><span is="qowt-word-run" qowt-eid="E274" id="E274" style="">;  John</span><span is="qowt-word-run" qowt-eid="E276" id="E276" style=""> 5:28-29; </span><span is="qowt-word-run" qowt-eid="E277" id="E277" style="">I Thes. </span><span is="qowt-word-run" qowt-eid="E280" id="E280" style="">4:13</span><span is="qowt-word-run" qowt-eid="E281" id="E281" style="">-18; I Cor. 15:12-20</span><span is="qowt-word-run" qowt-eid="E282" id="E282" style="">; Rev. 20:4-6; Rev. 20:11-15; </span> </p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E283" id="E283" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-teid="T25" style=""><br></span> </p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E284" id="E284" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E285" id="E285" style="">14.  THE CHURCH - That the</span><span is="qowt-word-run" qowt-eid="E286" id="E286" style=""> universal</span><span is="qowt-word-run" qowt-eid="E287" id="E287" style=""> </span><span is="qowt-word-run" qowt-eid="E289" id="E289" style="">church, </span><span is="qowt-word-run" qowt-eid="E290" id="E290" style=""> as</span><span is="qowt-word-run" qowt-eid="E292" id="E292" style=""> an </span><span is="qowt-word-run" qowt-eid="E293" id="E293" style="">organism, is the body and bride of Christ and includes the whole company of believers from Pentecost to the Rapture</span><span is="qowt-word-run" qowt-eid="E294" id="E294" style=""> and is known only to God.  This</span><span is="qowt-word-run" qowt-eid="E295" id="E295" style=""> </span><span is="qowt-word-run" qowt-eid="E296" id="E296" style="">local church, as an</span><span is="qowt-word-run" qowt-eid="E297" id="E297" style=""> organization is a </span> </p><p is="qowt-word-para" qowt-lvl="undefined" qowt-entry="undefined" qowt-eid="E298" id="E298" named-flow="FLOW-5" class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span is="qowt-word-run" qowt-eid="E299" id="E299" named-flow="FLOW-6" style="">company of believers baptized in the name of the Triune God and observing the form</span></p><p></p></qowt-page><p></p>'
              }
            }
          ]
        }
      }
    ]
  }
};

// Voyage example 59 (Button as wrapper with link)
const ex59: Data = {
  html: `<a href="/events" data-category="link" data-location="existing" data-detail="1446435" data-url="/events">
<button class="sites-button editable" data-id="27022666" data-category="button" tabindex="-1">
    <div class="sites-button-text">More</div>
</button></a>`,
  entry: { ...entry, selector: '[data-id="27022666"]' },
  output: {
    data: [
      {
        type: "Cloneable",
        value: {
          _id: "1",
          _styles: ["wrapper-clone", "wrapper-clone--button"],
          horizontalAlign: "center",
          items: [
            {
              type: "Button",
              value: {
                _id: "1",
                _styles: ["button"],
                borderStyle: "none",
                fontFamily: "lato",
                fontFamilyType: "upload",
                fontStyle: "",
                iconName: "",
                lineHeight: 1.3,
                linkExternal: "/events",
                linkExternalBlank: "on",
                linkType: "external",
                mobileFontStyle: "",
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontStyle: "",
                tabletLineHeight: 1.2,
                text: "More"
              }
            }
          ]
        }
      }
    ]
  }
};

// Voyage example 60 (Button as wrapper without link)
const ex60: Data = {
  html: `<button class="sites-button editable" data-id="27022666" data-category="button" tabindex="-1">
    <div class="sites-button-text">More</div></a>`,
  entry: { ...entry, selector: '[data-id="27022666"]' },
  output: {
    data: [
      {
        type: "Cloneable",
        value: {
          _id: "1",
          _styles: ["wrapper-clone", "wrapper-clone--button"],
          horizontalAlign: "center",
          items: [
            {
              type: "Button",
              value: {
                _id: "1",
                _styles: ["button"],
                borderStyle: "none",
                fontFamily: "lato",
                fontFamilyType: "upload",
                fontStyle: "",
                iconName: "",
                lineHeight: 1.3,
                mobileFontStyle: "",
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontStyle: "",
                tabletLineHeight: 1.2,
                text: "More"
              }
            }
          ]
        }
      }
    ]
  }
};

// Zion example 61 (Duplicated text)
const ex61: Data = {
  html: `<div class="text-content text-2 editable" data-id="26918095" data-category="text"><div><p><span style="font-size: 1.092em; letter-spacing: 0em;">This fall we took three weeks to remember who we are and who God is calling us to be. Our </span><em data-start="242" data-end="257" style="font-size: 1.092em; letter-spacing: 0em;">Re-Membership</em><span style="font-size: 1.092em; letter-spacing: 0em;"> series walked us through the call to </span><strong data-start="295" data-end="345" style="font-size: 1.092em; letter-spacing: 0em;">Do No Harm, Do Good, and Stay in Love with God</strong><span style="font-size: 1.092em; letter-spacing: 0em;">—an invitation to live deeply into the grace and life of this community.</span></p><p data-start="421" data-end="670">If you missed a week, or if you’d like to revisit the series, you can watch all three messages on our YouTube playlist here:<br data-start="545" data-end="548">
👉 <a data-start="551" data-end="668" rel="noopener" target="_new" href="https://youtube.com/playlist?list=PLeQuql1Woh7UNKlLIlM2c3bv2YTaK1LC4&amp;feature=shared">Watch the Re-Membership Series<span aria-hidden="true" class="clovercustom"><svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg" data-rtl-flip=""><path d="M14.3349 13.3301V6.60645L5.47065 15.4707C5.21095 15.7304 4.78895 15.7304 4.52925 15.4707C4.26955 15.211 4.26955 14.789 4.52925 14.5293L13.3935 5.66504H6.66011C6.29284 5.66504 5.99507 5.36727 5.99507 5C5.99507 4.63273 6.29284 4.33496 6.66011 4.33496H14.9999L15.1337 4.34863C15.4369 4.41057 15.665 4.67857 15.665 5V13.3301C15.6649 13.6973 15.3672 13.9951 14.9999 13.9951C14.6327 13.9951 14.335 13.6973 14.3349 13.3301Z"></path></svg></span></a></p><p>


</p><p data-start="672" data-end="794">This content will also serve as a foundation for our Re-membership journey at OKC First, so we’d love for you to take part.</p></div></div>`,
  entry: { ...entry, selector: '[data-id="26918095"]' },
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
                text: '<p class="brz-fs-lg-1_09 brz-ff-lato brz-ft-upload brz-fw-lg-bold brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="">This fall we took three weeks to remember who we are and who God is calling us to be. Our </span><em data-start="242" data-end="257" style="">Re-Membership</em><span style=""> series walked us through the call to </span><strong data-start="295" data-end="345" style="font-weight: bold; ">Do No Harm, Do Good, and Stay in Love with God</strong><span style="">—an invitation to live deeply into the grace and life of this community.</span></p>'
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
                text: '<p data-start="421" data-end="670" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>If you missed a week, or if you’d like to revisit the series, you can watch all three messages on our YouTube playlist here:</span><br data-start="545" data-end="548"><span> 👉 </span></p>'
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><a data-start="551" data-end="668" rel="noopener" target="_new" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fyoutube.com%2Fplaylist%3Flist%3DPLeQuql1Woh7UNKlLIlM2c3bv2YTaK1LC4%26feature%3Dshared%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span>Watch the Re-Membership Series</span></a></p>'
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
          horizontalAlign: undefined,
          items: [
            {
              type: "Icon",
              value: {
                _id: "1",
                _styles: ["icon"],
                code: '<svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg" data-rtl-flip=""><path d="M14.3349 13.3301V6.60645L5.47065 15.4707C5.21095 15.7304 4.78895 15.7304 4.52925 15.4707C4.26955 15.211 4.26955 14.789 4.52925 14.5293L13.3935 5.66504H6.66011C6.29284 5.66504 5.99507 5.36727 5.99507 5C5.99507 4.63273 6.29284 4.33496 6.66011 4.33496H14.9999L15.1337 4.34863C15.4369 4.41057 15.665 4.67857 15.665 5V13.3301C15.6649 13.6973 15.3672 13.9951 14.9999 13.9951C14.6327 13.9951 14.335 13.6973 14.3349 13.3301Z"></path></svg>',
                customSize: 26,
                filename: "icon.svg",
                linkExternal:
                  "https://youtube.com/playlist?list=PLeQuql1Woh7UNKlLIlM2c3bv2YTaK1LC4&feature=shared",
                linkExternalBlank: "on",
                linkType: "external",
                name: "favourite-31",
                padding: 7,
                type: "custom"
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
                text: '<p data-start="672" data-end="794" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>This content will also serve as a foundation for our Re-membership journey at OKC First, so we’d love for you to take part.</span></p>'
              }
            }
          ]
        }
      }
    ]
  }
};

// Zion example 62 (Large images inside text)
const ex62: Data = {
  html: `<div class="text-content text-2 editable" data-id="25987866" data-category="text"><div><p><a href="https://s3.amazonaws.com/media.cloversites.com/c9/c9abfbb4-cae8-470e-9903-47d102d29e9f/documents/Micah_Needs_List_summer.pdf" data-location="upload" data-button="false" data-detail="Micah_Needs_List_summer.pdf" data-category="document" target="_blank" class="cloverlinks">Micah_Needs_List_summer.pdf</a></p><p style="font-family: &quot;Proxima Nova&quot;, &quot;Proxima Nova Regular&quot;, sans-serif; font-weight: 200; font-size: 0.9158em;"><span style="letter-spacing: 0em;">Micah is a non-profit that ministers to those experiencing homelessness in the underserved northwest part of our city. They were hosted here at OKC First until June when they opened a drop-in day shelter around the corner on Meridian!&nbsp;&nbsp;</span></p><p style="font-family: &quot;Proxima Nova&quot;, &quot;Proxima Nova Regular&quot;, sans-serif; font-weight: 200; font-size: 0.9158em;"><span style="letter-spacing: 0em;">&nbsp;</span><span class="clovercustom" style="letter-spacing: 0em; text-align: inherit;"><img height="16" width="16" alt="📌" referrerpolicy="origin-when-cross-origin" src="https://static.xx.fbcdn.net/images/emoji.php/v9/tac/1/16/1f4cc.png"><u>Every</u> Sunday after church, since December, a team of OKC First volunteers goes across the street to a tent encampment.&nbsp;</span><span style="letter-spacing: 0em;">They take vital supplies and </span><span style="letter-spacing: 0em;">extend friendship and dignity to our neighbors experiencing homelessness.&nbsp;</span></p><p style="font-family: &quot;Proxima Nova&quot;, &quot;Proxima Nova Regular&quot;, sans-serif; font-weight: 200; font-size: 0.9158em;"><img height="16" width="16" alt="📌" referrerpolicy="origin-when-cross-origin" src="https://static.xx.fbcdn.net/images/emoji.php/v9/tac/1/16/1f4cc.png" style="font-size: 16.0009px; font-style: normal; font-weight: 200; letter-spacing: normal; text-align: left;">The Micah Community Center opened in June and is open three part time days a week. This is the only homelessness services facility west of I-44!! They provide respite from the weather, a hot meal, device charging, TV lounge, and personal care items. Showers and laundry service are coming soon. They need volunteers, resource donations, and financial support!&nbsp;<br></p><p style="font-family: &quot;Proxima Nova&quot;, &quot;Proxima Nova Regular&quot;, sans-serif; font-weight: 200; font-size: 0.9158em;"><br></p><p style="font-family: &quot;Proxima Nova&quot;, &quot;Proxima Nova Regular&quot;, sans-serif; font-weight: 200; font-size: 0.9158em;">You can join the Micah Movement! To participate in our outreach team or volunteer at the Micah Center: contact <a style="font-weight: 600;" href="mailto:micahcommunitymovement@gmail.com" data-location="email" data-button="false" data-detail="micahcommunitymovement@gmail.com" data-category="link" target="_self" class="cloverlinks"><u style="">Alissa Gilmore</u></a>. Donate funds on the <a href="http://giving.okcfirst.com" data-location="external" data-button="false" data-detail="http://giving.okcfirst.com" data-category="link" target="_blank" class="cloverlinks" style="font-weight: 600;"><u style="">church's giving site</u></a> and select Micah, or at <a href="http://mcmokc.org/donate" data-location="external" data-button="false" data-detail="http://mcmokc.org/donate" data-category="link" target="_blank" class="cloverlinks" style="font-weight: 600;"><u>www.mcmokc.org/donate</u></a>. And contribute <u style=""><a href="https://s3.amazonaws.com/media.cloversites.com/c9/c9abfbb4-cae8-470e-9903-47d102d29e9f/documents/Micah_Needs_List_summer.pdf" data-location="existing" data-button="false" data-detail="Micah_Needs_List_summer.pdf" data-category="document" target="_blank" class="cloverlinks" style="font-weight: 600;">most needed items.</a></u>&nbsp;</p><p style="font-family: &quot;Proxima Nova&quot;, &quot;Proxima Nova Regular&quot;, sans-serif; font-weight: 200; font-size: 0.9158em;"><br></p><p style="font-family: &quot;Proxima Nova&quot;, &quot;Proxima Nova Regular&quot;, sans-serif; font-weight: 200; font-size: 0.9158em;"><img height="16" width="16" alt="📌" referrerpolicy="origin-when-cross-origin" src="https://static.xx.fbcdn.net/images/emoji.php/v9/tac/1/16/1f4cc.png" style="font-size: 16.0009px; font-style: normal; font-weight: 200; letter-spacing: normal; text-align: left;">Store this number in your phone: <span class="clovercustom" style="font-weight: 600;">405-498-2009</span>. If you encounter a person experiencing homelessness in the area near the church, give them the number to call/text our Micah team. It is NOT an emergency number; they may need to leave a message. They have Center invite cards if you would like to keep some handy in your car to give out.&nbsp;</p></div></div>`,
  entry: { ...entry, selector: '[data-id="25987866"]' },
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><a data-location="upload" data-button="false" data-detail="Micah_Needs_List_summer.pdf" data-category="document" target="_blank" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fs3.amazonaws.com%2Fmedia.cloversites.com%2Fc9%2Fc9abfbb4-cae8-470e-9903-47d102d29e9f%2Fdocuments%2FMicah_Needs_List_summer.pdf%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span>Micah_Needs_List_summer.pdf</span></a></p><p class="brz-fs-lg-0_92 brz-ff-lato brz-ft-upload brz-fw-lg-200 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 200; ">Micah is a non-profit that ministers to those experiencing homelessness in the underserved northwest part of our city. They were hosted here at OKC First until June when they opened a drop-in day shelter around the corner on Meridian!&nbsp;&nbsp;</span></p>'
              }
            }
          ]
        }
      },
      {
        type: "Wrapper",
        value: {
          _id: "1",
          _styles: ["wrapper", "wrapper--image"],
          horizontalAlign: "",
          items: [
            {
              type: "Image",
              value: {
                alt: "📌",
                height: 0,
                heightSuffix: "px",
                imageSrc:
                  "https://static.xx.fbcdn.net/images/emoji.php/v9/tac/1/16/1f4cc.png",
                linkExternal: "",
                linkExternalBlank: "off",
                linkType: "external",
                sizeType: "custom",
                width: 0,
                widthSuffix: "px"
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
                text: '<p class="brz-fs-lg-0_92 brz-ff-lato brz-ft-upload brz-fw-lg-200 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="">&nbsp;</span><span style="font-weight: 200; "><u><span>Every</span></u> Sunday after church, since December, a team of OKC First volunteers goes across the street to a tent encampment.&nbsp;</span><span style="font-weight: 200; ">They take vital supplies and </span><span style="font-weight: 200; ">extend friendship and dignity to our neighbors experiencing homelessness.&nbsp;</span></p>'
              }
            }
          ]
        }
      },
      {
        type: "Wrapper",
        value: {
          _id: "1",
          _styles: ["wrapper", "wrapper--image"],
          horizontalAlign: "",
          items: [
            {
              type: "Image",
              value: {
                alt: "📌",
                height: 0,
                heightSuffix: "px",
                imageSrc:
                  "https://static.xx.fbcdn.net/images/emoji.php/v9/tac/1/16/1f4cc.png",
                linkExternal: "",
                linkExternalBlank: "off",
                linkType: "external",
                sizeType: "custom",
                width: 0,
                widthSuffix: "px"
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
                text: '<p class="brz-fs-lg-0_92 brz-ff-lato brz-ft-upload brz-fw-lg-200 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 200; ">The Micah Community Center opened in June and is open three part time days a week. This is the only homelessness services facility west of I-44!! They provide respite from the weather, a hot meal, device charging, TV lounge, and personal care items. Showers and laundry service are coming soon. They need volunteers, resource donations, and financial support!&nbsp;</span><br></p><p class="brz-fs-lg-0_92 brz-ff-lato brz-ft-upload brz-fw-lg-200 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-0_92 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 200; ">You can join the Micah Movement! To participate in our outreach team or volunteer at the Micah Center: contact </span><a style="font-weight: 600; " data-location="email" data-button="false" data-detail="micahcommunitymovement@gmail.com" data-category="link" target="_self" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22mailto%3Amicahcommunitymovement%40gmail.com%22%2C%22externalBlank%22%3A%22off%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><u style=""><span>Alissa Gilmore</span></u></a><span style="font-weight: 200; ">. Donate funds on the </span><a data-location="external" data-button="false" data-detail="http://giving.okcfirst.com" data-category="link" target="_blank" style="font-weight: 600; " data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22http%3A%2F%2Fgiving.okcfirst.com%2F%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><u style=""><span>church\'s giving site</span></u></a><span style="font-weight: 200; "> and select Micah, or at </span><a data-location="external" data-button="false" data-detail="http://mcmokc.org/donate" data-category="link" target="_blank" style="font-weight: 600; " data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22http%3A%2F%2Fmcmokc.org%2Fdonate%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><u><span>www.mcmokc.org/donate</span></u></a><span style="font-weight: 200; ">. And contribute </span><u style=""><a data-location="existing" data-button="false" data-detail="Micah_Needs_List_summer.pdf" data-category="document" target="_blank" style="font-weight: 600; " data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fs3.amazonaws.com%2Fmedia.cloversites.com%2Fc9%2Fc9abfbb4-cae8-470e-9903-47d102d29e9f%2Fdocuments%2FMicah_Needs_List_summer.pdf%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span style="font-weight: 600; ">most needed items.</span></a></u>&nbsp;</p><p class="brz-fs-lg-0_92 brz-ff-lato brz-ft-upload brz-fw-lg-200 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p>'
              }
            }
          ]
        }
      },
      {
        type: "Wrapper",
        value: {
          _id: "1",
          _styles: ["wrapper", "wrapper--image"],
          horizontalAlign: "",
          items: [
            {
              type: "Image",
              value: {
                alt: "📌",
                height: 0,
                heightSuffix: "px",
                imageSrc:
                  "https://static.xx.fbcdn.net/images/emoji.php/v9/tac/1/16/1f4cc.png",
                linkExternal: "",
                linkExternalBlank: "off",
                linkType: "external",
                sizeType: "custom",
                width: 0,
                widthSuffix: "px"
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
                text: '<p class="brz-fs-lg-0_92 brz-ff-lato brz-ft-upload brz-fw-lg-200 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 200; ">Store this number in your phone: </span><span style="font-weight: 600; ">405-498-2009</span><span style="font-weight: 200; ">. If you encounter a person experiencing homelessness in the area near the church, give them the number to call/text our Micah team. It is NOT an emergency number; they may need to leave a message. They have Center invite cards if you would like to keep some handy in your car to give out.&nbsp;</span></p>'
              }
            }
          ]
        }
      }
    ]
  }
};

// Tradition example 63 (Header tag inside text)
const ex63: Data = {
  html: `<header class="text-content text-0 title-text editable" data-id="19074833" data-category="text"><div><div style="font-size: 11.9988px; font-family: &quot;Linden Hill&quot;, serif; font-weight: 700; font-style: normal; line-height: 20.398px; text-align: center; letter-spacing: normal; text-transform: none; color: rgb(148, 79, 0);"><br></div><div style="font-size: 36px; font-family: &quot;Linden Hill&quot;, serif; font-weight: 700; font-style: normal; line-height: 61.2px; text-align: center; letter-spacing: normal; text-transform: none; color: rgb(148, 79, 0);"><a href="/home/worship-sacraments" data-location="existing" data-button="false" data-detail="1046868" data-category="link" target="_self" class="cloverlinks" style="color: rgb(148, 79, 0);"><u>Worship &amp; Sacraments</u></a></div></div></header>`,
  entry: { ...entry, selector: '[data-id="19074833"]' },
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
                text: '<p class="brz-fs-lg-12 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-0"><br></p><p class="brz-fs-lg-36 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-0"><a data-location="existing" data-button="false" data-detail="1046868" data-category="link" target="_self" style="color: rgb(148, 79, 0); " data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22%2Fhome%2Fworship-sacraments%22%2C%22externalBlank%22%3A%22off%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><u><span>Worship &amp; Sacraments</span></u></a></p>'
              }
            }
          ]
        }
      }
    ]
  }
};

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
  ex13,
  ex14,
  ex15,
  ex16,
  ex17,
  ex18,
  ex19,
  ex20,
  ex21,
  ex22,
  ex23,
  ex24,
  ex25,
  ex26,
  ex27,
  ex28,
  ex29,
  ex30,
  ex31,
  ex32,
  ex33,
  ex34,
  ex35,
  ex36,
  ex37,
  ex38,
  ex39,
  ex40,
  ex41,
  ex42,
  ex43,
  ex44,
  ex45,
  ex46,
  ex47,
  ex48,
  ex49,
  ex50,
  ex51,
  ex52,
  ex53_1,
  ex53_2,
  ex54,
  ex55,
  ex56,
  ex57,
  ex58,
  ex59,
  ex60,
  ex61,
  ex62,
  ex63
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
