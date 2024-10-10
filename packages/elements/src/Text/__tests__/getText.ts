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
          horizontalAlign: undefined,
          items: [
            {
              type: "Button",
              value: {
                _id: "1",
                _styles: ["button"],
                text: "I'm New ",
                borderStyle: "none",
                fontFamily: "lato",
                fontFamilyType: "upload",
                fontSize: 1,
                fontStyle: "",
                fontWeight: 600,
                iconName: "arrow-alt-circle-right",
                letterSpacing: NaN,
                lineHeight: 1.3,
                linkExternal: "/about-us/who-we-are",
                linkType: "external",
                mobileFontSize: 1,
                mobileFontStyle: "",
                mobileFontWeight: 600,
                mobileLetterSpacing: NaN,
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontSize: 1,
                tabletFontStyle: "",
                tabletFontWeight: 600,
                tabletLetterSpacing: NaN,
                tabletLineHeight: 1.2,
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Please </span><span style="font-weight: 700; ">Request Online Directory Account</span><span> to create a User Account to login into the Directory portal.&nbsp; &nbsp;Please insert your Name, Address, Phone and Email, so we can update our records.&nbsp; &nbsp;Please be patient with us, we are getting our records in order and it make take a couple days before you get an email with your login credentials.&nbsp;</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 700; ">Directory </span><span>will redirect to </span><span style="font-weight: 400; "><span style="font-weight: 700; ">Shelby Next Membership</span> at <a data-location="external" data-detail="https://fbcterry.shelbynextchms.com" data-category="link" target="_blank" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Ffbcterry.shelbynextchms.com%2F%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span>https://fbcterry.shelbynextchms.com</span></a></span><span>.</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Other ways to access </span><span style="font-weight: 700; ">Shelby Next Membership</span><span> can be found at your Apple App Store or Google Play Store.&nbsp;</span><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>For mobile apps, change </span><span style="font-weight: 700; ">domain</span><span>.shelbynextchms.com to </span><u><span style="font-weight: 700; ">fbcterry</span><span>.shelbynextchms.com</span></u><span>.&nbsp;&nbsp;</span></p><ul> <li class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"> <a data-category="link" data-location="external" data-detail="https://apps.apple.com/us/app/shelbynext-membership/id996581065" target="_self" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fapps.apple.com%2Fus%2Fapp%2Fshelbynext-membership%2Fid996581065%22%2C%22externalBlank%22%3A%22off%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span>iPhone/iPad app for Shelby Next Membership</span></a>  </li> <li class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"> <a data-category="link" data-location="external" data-detail="https://play.google.com/store/apps/details?id=com.ministrybrands.shelbynext" target="_self" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fplay.google.com%2Fstore%2Fapps%2Fdetails%3Fid%3Dcom.ministrybrands.shelbynext%22%2C%22externalBlank%22%3A%22off%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span>Android app for Shelby Next Membership</span></a>   <p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p> </li> </ul><p class="brz-fs-lg-0 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-0_0"><span style="font-weight: 700; ">Calendar </span><span>is our Church-wide calendar.</span><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p>'
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
                text: `<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span>Address: 404 Church St. Columbia, LA 71418</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span>Phone Number: 318-649-2202</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span>Fax Number: 318-649-2206</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span>Email: fbcbeyond@bellsouth.net</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><br></p>`
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0">  <span>  [social=circleundefined]</span><br></p>'
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
                text: `<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; "> We are a church whose goal is to make followers of Jesus Christ.&nbsp;</em></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">We believe that Jesus Christ died on the cross for our sins,</em></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; "> was buried, and on the third day rose from the dead!&nbsp;</em></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">We believe that everyone who turns from their sins and believes in Jesus Christ will be saved. How?&nbsp;</em></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">One calls on the name of Jesus for salvation!</em></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span style="color: rgb(230, 230, 189); font-weight: 700; ">Our worship services here in Shady<span style="color: rgb(230, 230, 189); font-weight: 700; "> Side, Maryla</span>nd are 10–11am each Sunday morning</span><em style="color: rgb(230, 230, 189); font-weight: 700; ">.</em></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">During this time, we will read Scripture, pray together, sing worship songs,&nbsp;</em></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">and listen to the Bible preached.</em></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">We hope you will come and feel welcome as you listen to God’s word to us in the Bible.&nbsp;</em></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">Our Pastor and Elders are here for you if you have any questions about our church, baptism, or</em></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">following Jesus. Our atmosphere is casual, so do not feel like you need to dress up to attend.&nbsp;</em></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">We hope you will browse our website to find out more about our church,&nbsp;</em></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><em style="color: rgb(237, 227, 225); font-weight: 700; ">our Baptist beliefs, and our ministries.</em></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><em style="color: red; ">Test</em></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0">&nbsp;</p>`
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p data-uniq-id="sSLWq" data-generated-css="brz-css-hYqTF" class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><u><span>Be inspired by our designs where modern lines blend with classic elements to create harmony and coziness. We play with colors, textures and light to ensure that every corner of your home is filled with warmth and style.</span></u></span></p><p data-uniq-id="sSLWq" data-generated-css="brz-css-hYqTF" class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-right brz-ls-lg-NaN_0"><span style="font-weight: inherit; color: rgb(235, 9, 9); ">Our team of talented designers and architects are ready to make your dreams come true.&nbsp;</span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-2 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><span style="font-weight: inherit; ">Our team of talented designers and architects are ready to make your dreams come true.&nbsp;</span><br></span></p><p data-uniq-id="lUfdT" data-generated-css="brz-css-m4s3A" class="brz-fs-lg-19 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-m_0_1"><span style="font-weight: inherit; "><span style=""><br></span></span></p><ul><li data-uniq-id="vteAp" data-generated-css="brz-css-hf4DG" style="color: rgb(70, 242, 12); " class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-inherit brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: inherit; color: rgb(70, 242, 12); ">We approach each project carefully, taking into account your preferences, budget and individual space features.</span></li><li data-uniq-id="vteAp" data-generated-css="brz-css-hf4DG" style="color: rgb(222, 95, 22); " class="brz-fs-lg-14 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-m_0_1"><span style="color: rgb(222, 95, 22); ">We approach each project carefully, taking into account your preferences, budget and individual space features.</span></li></ul>'
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
                fontSize: NaN,
                fontStyle: "",
                fontWeight: NaN,
                letterSpacing: NaN,
                mobileFontSize: NaN,
                mobileFontStyle: "",
                mobileFontWeight: NaN,
                mobileLetterSpacing: NaN,
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontSize: NaN,
                tabletFontStyle: "",
                tabletFontWeight: NaN,
                tabletLetterSpacing: NaN,
                tabletLineHeight: 1.2,
                lineHeight: 1.3,
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
                fontSize: NaN,
                fontStyle: "",
                fontWeight: NaN,
                letterSpacing: NaN,
                lineHeight: 1.3,
                mobileFontSize: NaN,
                mobileFontStyle: "",
                mobileFontWeight: NaN,
                mobileLetterSpacing: NaN,
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontSize: NaN,
                tabletFontStyle: "",
                tabletFontWeight: NaN,
                tabletLetterSpacing: NaN,
                tabletLineHeight: 1.2,
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Register Here!&nbsp;</span><br> </p>'
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span>Address: 404 Church St. Columbia, LA 71418</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span>Phone Number: 318-649-2202</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span>Fax Number: 318-649-2206</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span>Email: fbcbeyond@bellsouth.net</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><br></p>'
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0">  <span>  [social=circleundefined]</span></p>'
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0">  <span>  [social=circleundefined]</span></p>'
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
                fontSize: NaN,
                fontStyle: "",
                fontWeight: NaN,
                letterSpacing: NaN,
                lineHeight: 1.3,
                mobileFontSize: NaN,
                mobileFontStyle: "",
                mobileFontWeight: NaN,
                mobileLetterSpacing: NaN,
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontSize: NaN,
                tabletFontStyle: "",
                tabletFontWeight: NaN,
                tabletLetterSpacing: NaN,
                tabletLineHeight: 1.2,
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
                fontFamily: "lato",
                fontFamilyType: "upload",
                fontSize: NaN,
                fontStyle: "",
                fontWeight: NaN,
                letterSpacing: NaN,
                lineHeight: 1.3,
                mobileFontSize: NaN,
                mobileFontStyle: "",
                mobileFontWeight: NaN,
                mobileLetterSpacing: NaN,
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontSize: NaN,
                tabletFontStyle: "",
                tabletFontWeight: NaN,
                tabletLetterSpacing: NaN,
                tabletLineHeight: 1.2,
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
                text: "I'm New ",
                borderStyle: "none",
                fontFamily: "lato",
                fontFamilyType: "upload",
                fontSize: 1,
                fontStyle: "",
                fontWeight: 600,
                letterSpacing: NaN,
                lineHeight: 1.3,
                mobileFontSize: 1,
                mobileFontStyle: "",
                mobileFontWeight: 600,
                mobileLetterSpacing: NaN,
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontSize: 1,
                tabletFontStyle: "",
                tabletFontWeight: 600,
                tabletLetterSpacing: NaN,
                tabletLineHeight: 1.2,
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
                text: `<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><u><span>Nursery</span></u>&nbsp;&nbsp;</p><ul><li style="color: rgb(228, 233, 238); " class="brz-mt-lg-15 brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(228, 233, 238); ">We have 4 spots open in our nursery!</span></li><li style="color: rgb(228, 233, 238); " class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(228, 233, 238); ">The nursery includes babies and toddlers from 6 weeks old through 2 years, 11 months</span></li><li style="color: rgb(228, 233, 238); " class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(228, 233, 238); ">All volunteers must be willing to submit to a general background check</span></li><li style="color: rgb(228, 233, 238); " class="brz-mb-lg-15 brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(228, 233, 238); ">Please see our bulletin board in the main hallway or ask Bailey Stewart for a description of the specific duties and roles</span></li></ul><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><u><span>Children's Church (ARROW Kids)</span></u></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"></p><ul><li class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>We have 4 spots open with ARROW Kids Sunday Morning Children's Church!&nbsp;</span></li><li class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>ARROW Kids is designed for children ages 3 through 10</span></li><li class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Volunteers are provided a fun, easy to follow lesson plan each week including Bible stories, games, and crafts</span></li><li class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>All volunteers must be willing to submit to a general background check</span></li><li class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Please see our bulletin board in the main hallway, or ask Crystal Williford, for more information on duties and roles</span></li></ul><p></p>`
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
                text: '<p class="brz-fs-lg-2 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); font-weight: 400; ">What is it?</span></p><p class="brz-fs-lg-0 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><o:p>&nbsp;</o:p></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">Baptism is an outward expression of an inward faith. It’s showing everyone that you’ve confessed (you’ve said it out loud) that Jesus is Lord and believed in your heart God raised Him from the dead. Baptism is a way to symbolize the death and resurrection of Christ in our lives as believers.</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><o:p>&nbsp;</o:p></p><p class="brz-fs-lg-2 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 400; color: rgb(2, 78, 105); ">Why submersion?</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">We believe that by being placed completely underwater, baptism by submersion symbolizes the death and resurrection of Jesus in our lives as believers. Complete submersion also represents the total spiritual change a person experiences by being rescued and saved by Jesus.</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><o:p>&nbsp;</o:p></p><p class="brz-fs-lg-2 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span lang="EN-US" style="font-weight: 400; color: rgb(2, 78, 105); ">Who should be baptized?</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span lang="EN-US" style="color: rgb(2, 78, 105); ">If you have confessed with your mouth that Jesus is Lord, repented of your sin, and believed that God raised Him from the dead, and you’ve never been baptized before, your next step is to be baptized. Confessing, repenting, and believing makes you a Christian and all Christians are commanded by Jesus to be baptized. (Matthew 28:19) We encourage you to take your next step as a believer to be baptized and publicly share your faith in Him.</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><o:p>&nbsp;</o:p></p><p class="brz-fs-lg-2 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span lang="EN-US" style="font-weight: 400; color: rgb(2, 78, 105); ">How can I be baptized?</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><o:p>&nbsp;</o:p></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><o:p>&nbsp;</o:p><span style="color: rgb(2, 78, 105); ">We have baptisms throughout the year and would love for you to be a part of one of the next one! <a data-location="existing" data-detail="879092" data-category="link" target="_self" style="color: rgb(17, 125, 2); " data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22%2Fcontact-us%2Fdecision-form%22%2C%22externalBlank%22%3A%22off%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span style="color: rgb(17, 125, 2); ">Contact us</span></a> so we can discuss you next step.</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"></p>'
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
                fontSize: NaN,
                fontStyle: "",
                fontWeight: NaN,
                letterSpacing: NaN,
                lineHeight: 1.3,
                mobileFontSize: NaN,
                mobileFontStyle: "",
                mobileFontWeight: NaN,
                mobileLetterSpacing: NaN,
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontSize: NaN,
                tabletFontStyle: "",
                tabletFontWeight: NaN,
                tabletLetterSpacing: NaN,
                tabletLineHeight: 1.2,
                linkExternal: "/contact-us/decision-form",
                linkType: "external",
                linkExternalBlank: "off"
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
                text: '<p class="brz-fs-lg-34 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-0_1"><span style="font-weight: 700; ">How can I help?</span><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>We need dedicated volunteers to help prepare the baptismal every Baptismal Sunday.&nbsp; This includes set-up, tear-down, and providing necessary support to the ones being baptized.&nbsp;</span><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style=""><br></span></p>'
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
                fontSize: NaN,
                fontStyle: "",
                fontWeight: NaN,
                letterSpacing: NaN,
                lineHeight: 1.3,
                mobileFontSize: NaN,
                mobileFontStyle: "",
                mobileFontWeight: NaN,
                mobileLetterSpacing: NaN,
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontSize: NaN,
                tabletFontStyle: "",
                tabletFontWeight: NaN,
                tabletLetterSpacing: NaN,
                tabletLineHeight: 1.2,
                linkExternal:
                  "https://docs.google.com/forms/d/1Bt4ajELyGHt1jYtkf7X9QG_FTKcqX0p5r9xcxSCckYk/edit",
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
                text: `<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><style> </style></p>`
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
  html: `<div class="text-content text-0 editable" data-id="12866076" data-category="text"><div><p style="text-align: center; font-size: 1.0638em;">Follow us on:&nbsp;&nbsp;<a class="socialIconLink cloverlinks" style="font-size: 1.875em; color: rgb(94, 111, 224);" href="https://www.facebook.com/ConnectionMillen" data-location="external" data-button="false" data-detail="https://www.facebook.com/ConnectionMillen" data-category="link" target="_blank"><span data-icon="facebook"><span class="clovericons fab" aria-hidden="true"></span><span class="sr-only">Facebook</span></span></a><a class="socialIconLink cloverlinks" href="https://www.facebook.com/ConnectionMillen" data-location="external" data-button="false" data-detail="https://www.facebook.com/ConnectionMillen" data-category="link" target="_blank" style="font-size: 1.0638em; letter-spacing: 0.01em; background-color: rgb(80, 80, 80);"> </a>  &nbsp;&nbsp;<a class="socialIconLink cloverlinks" href="https://www.instagram.com/connectionchurchmillen/" data-location="external" data-button="false" data-detail="https://www.instagram.com/connectionchurchmillen/" data-category="link" target="_blank" style="letter-spacing: 0.01em; background-color: rgb(80, 80, 80); font-size: 1.875em;"><span data-icon="instagram"><span class="clovericons fab" style="color: rgb(214, 46, 4); font-size: 1em;" aria-hidden="true"></span><span class="sr-only">Instagram</span></span> </a> <a class="socialIconLink cloverlinks" style="color: rgb(250, 3, 3); font-size: 1.625em;" href="https://www.youtube.com/@ConnectionChurchMillen" data-location="external" data-button="false" data-detail="https://www.youtube.com/@ConnectionChurchMillen" data-category="link" target="_blank"><span data-socialicon="youtube"><span class="socialIconSymbol" aria-hidden="true"></span><span class="sr-only">youtube</span></span></a></p><p style="text-align: center; font-size: 1.0638em;">&nbsp; &nbsp; &nbsp;&nbsp;<br></p><p>Connect with us:&nbsp;</p><div><p><span class="clovercustom" style="font-size: 10pt; font-weight: 700;">Church Office Address</span></p><p><span class="clovercustom" style="font-size: 10pt;">1178 E. Winthrope Ave. Millen, GA 30442</span></p><p style="font-weight: 500; font-size: 0.9973em;"><br></p><p style="font-weight: 500; font-size: 0.9973em;">Sunday Service</p><p style="font-weight: 300;">Located in the cafeteria behind Jenkins County High School off North Ave.&nbsp;</p><p style="font-weight: 300;"><a href="https://maps.app.goo.gl/Sb33XGmMfvqtsSAt5" data-location="external" data-button="true" data-detail="https://maps.app.goo.gl/Sb33XGmMfvqtsSAt5" data-category="link" target="_blank" class="cloverlinks sites-button" role="button">Click Here for Directions</a></p><p><span class="clovercustom" style="font-size: 10pt;"><br></span></p></div></div></div>`,
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span>Follow us on:&nbsp;&nbsp;</span><a style="color: rgb(94, 111, 224); " data-location="external" data-button="false" data-detail="https://www.facebook.com/ConnectionMillen" data-category="link" target="_blank" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fwww.facebook.com%2FConnectionMillen%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"></a><a data-location="external" data-button="false" data-detail="https://www.facebook.com/ConnectionMillen" data-category="link" target="_blank" style="background-color: rgb(80, 80, 80); " data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fwww.facebook.com%2FConnectionMillen%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"> </a>  &nbsp;&nbsp;<a data-location="external" data-button="false" data-detail="https://www.instagram.com/connectionchurchmillen/" data-category="link" target="_blank" style="background-color: rgb(80, 80, 80); " data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fwww.instagram.com%2Fconnectionchurchmillen%2F%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"> </a> <a style="color: rgb(250, 3, 3); " data-location="external" data-button="false" data-detail="https://www.youtube.com/@ConnectionChurchMillen" data-category="link" target="_blank" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fwww.youtube.com%2F%40ConnectionChurchMillen%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"></a></p>'
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
                colorOpacity: "1",
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
                text: `<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0">&nbsp; &nbsp; &nbsp;&nbsp;<br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Connect with us:&nbsp;</span></p><p class="brz-fs-lg-10 brz-ff-lato brz-ft-upload brz-fw-lg-700 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 700; ">Church Office Address</span></p><p class="brz-fs-lg-10 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="">1178 E. Winthrope Ave. Millen, GA 30442</span></p><p class="brz-fs-lg-0 brz-ff-lato brz-ft-upload brz-fw-lg-500 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-0 brz-ff-lato brz-ft-upload brz-fw-lg-500 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 500; ">Sunday Service</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-300 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 300; ">Located in the cafeteria behind Jenkins County High School off North Ave.&nbsp;</span></p>`
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
                fontSize: NaN,
                fontStyle: "",
                fontWeight: NaN,
                letterSpacing: NaN,
                lineHeight: 1.3,
                mobileFontSize: NaN,
                mobileFontStyle: "",
                mobileFontWeight: NaN,
                mobileLetterSpacing: NaN,
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontSize: NaN,
                tabletFontStyle: "",
                tabletFontWeight: NaN,
                tabletLetterSpacing: NaN,
                tabletLineHeight: 1.2,
                linkExternal: "https://maps.app.goo.gl/Sb33XGmMfvqtsSAt5",
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
                text: '<p class="brz-fs-lg-2 brz-ff-lato brz-ft-upload brz-fw-lg-500 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><u><span>Who\'s your one for 2024?</span></u></p><p class="brz-fs-lg-2 brz-ff-lato brz-ft-upload brz-fw-lg-500 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-center brz-ls-lg-NaN_0"><span style="font-weight: 500; ">Who\'s the one you are praying for this year to know Jesus?</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Who\'s the one you are investing in this year?</span></p><p dir="auto" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Who\'s the one you are allowing to invest in you this year?</span></p><p dir="auto" class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p dir="auto" class="brz-fs-lg-0 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><em>If you would like to tell us stories of your journey, we\'d love to hear them!!&nbsp; </em><a data-location="external" data-button="false" data-detail="https://docs.google.com/forms/d/1_AX6wHW5zGDd6082vCXVTC2QQbmFufL8ABmFqdQKU6Q/edit#settings" data-category="link" target="_blank" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fdocs.google.com%2Fforms%2Fd%2F1_AX6wHW5zGDd6082vCXVTC2QQbmFufL8ABmFqdQKU6Q%2Fedit%23settings%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span>Click Here</span></a><em> to share.</em></p><p dir="auto" class="brz-fs-lg-0 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><em>If you need some help finding someone to invest in or to invest in you, </em><a data-location="external" data-button="false" data-detail="https://docs.google.com/forms/d/1LA-3CfWM4edzYng-vC26G5cNWddGtuUVAXkiYCAoWm8/edit" data-category="link" target="_blank" data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fdocs.google.com%2Fforms%2Fd%2F1LA-3CfWM4edzYng-vC26G5cNWddGtuUVAXkiYCAoWm8%2Fedit%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span>click here</span></a><em> and let us walk with you and help you on your journey.</em></p>'
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
                text: `<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">Why do we give to the local church?</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><style></style></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">Jesus said that where your treasure is, your heart is there also. What He means is that if our money is our treasure, our heart belongs to our wallets and not to Him. The Bible also speaks about being a good steward of all the blessings God has given you, including your money. So we want to honor Him by giving back a portion of what He has so generously given to us.</span></p>`
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span lang="EN-US" style="font-weight: 400; color: rgb(2, 78, 105); ">Why do we give to the local church?</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><style></style></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">Jesus said that where your treasure is, your heart is there also. What He means is that if our money is our treasure, our heart belongs to our wallets and not to Him. The Bible also speaks about being a good steward of all the blessings God has given you, including your money. So we want to honor Him by giving back a portion of what He has so generously given to us.</span></p>'
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
  entry: {...entry, selector: "[data-id=\"13228982\"]"},
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 400; color: rgb(2, 78, 105); ">THE INERRANCY OF THE BIBLE</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">The scriptures, which include the Old and New Testaments, are divinely inspired and represent the infallible and authoritative word of God in all matters of faith and conduct. (2 Timothy 3:15-17; 1 Thessalonians 2:13; 2 Peter 1:21)</span><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 400; color: rgb(2, 78, 105); ">BELIEF IN THE ONE TRUE GOD</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">There is one eternal God, existent in three persons- God the Father, God the Son, and God the Holy Spirit. (Deuteronomy 6:4; Isaiah 43:10-11; Matthew 28:19; Luke 3:22)</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span lang="EN-US" style="font-weight: 400; color: rgb(2, 78, 105); ">JESUS CHRIST IS FULLY GOD AND FULLY MAN</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">The Lord Jesus Christ is the eternal Son of God. Born of a virgin. (Matthew 1:23; Luke 1:31-35) Lived a sinless life. (Hebrews 7:26; 1 Peter 2:22; Hebrews 4:15) Died on the cross to atone for our sins. (1 Corinthians 15:3; 2 Corinthians 5:21) Physically rose from the dead. (Acts 1:9; Acts 2:32; Hebrews 1:3) Now sits at the right hand of His Father. (Acts 2:33; Hebrews 10:12) He will return to earth in power and glory (Zechariah 14:5; Matthew 24:27; Revelation 19:11-14)</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); font-weight: 400; ">SALVATION</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">Man\'s only hope of redemption is through the shed blood of Jesus Christ. We are saved by grace through faith when we accept Jesus Christ as our Lord and Savior, and we are cleansed from sin through repentance and regeneration of the Holy Spirit. (John 3:3; Luke 24:46-47; Romans 10:13; Ephesians 2:8; Titus 3:5-7; John 6:63)</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); font-weight: 400; ">HOLY SPIRIT</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">The Holy Spirit, our abiding Helper, Teacher and Guide, indwells and empowers every believer in Jesus Christ. (Romans 8:11; Romans 8:26-27; John 14:16-17; Isaiah 61; Acts 1:8; 1 Corinthians 2:10)</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); font-weight: 400; ">THE CHURCH</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">We believe the church is the body of Christ, the fellowship of all believers. Christians have been called out from the world to obey the teachings of Christ and serve as His ambassadors and co-laborers. (Matthew 18:18-20; John 15:19-20; Ephesians 1:22-23; Ephesians 5:23; 1 Corinthians 3:9; 2 Corinthians 5:20)</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); font-weight: 400; ">BAPTISM</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">While we recognize that the act of Baptism is observed in varying ways amongst different fellowships, it is our conviction and practice to baptize new believers by submersion. (Matthew 28:19; Acts 10:47)</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); font-weight: 400; ">HOLY COMMUNION</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">The Lord’s Supper is a symbolic act of obedience in which we partake of the bread and the fruit of the vine, remembering the death of Jesus and anticipating His second coming. (1 Corinthians 11:23-26)</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-400 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); font-weight: 400; ">ETERNITY</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(2, 78, 105); ">There will be a final judgment and the resurrection of both the saved and the lost; one to everlasting life and the other to everlasting damnation. (Matthew 25:46; Mark 9:43-48; Revelation 19:20, Revelation 21:8)</span></p>'
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
                fontSize: NaN,
                fontStyle: "",
                fontWeight: NaN,
                letterSpacing: NaN,
                lineHeight: 1.3,
                mobileFontSize: NaN,
                mobileFontStyle: "",
                mobileFontWeight: NaN,
                mobileLetterSpacing: NaN,
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontSize: NaN,
                tabletFontStyle: "",
                tabletFontWeight: NaN,
                tabletLetterSpacing: NaN,
                tabletLineHeight: 1.2,
                linkExternal: "/about-us/who-we-are",
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="font-weight: 600; color: rgb(0, 0, 0); ">Timberwood Church is a Bible-based church designed to help people experience </span><span style="font-weight: 600; color: rgb(0, 0, 0); ">God’s goodness in their lives.</span>&nbsp;</p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(0, 0, 0); ">Through teaching and contemporary musical worship, we seek to make the timeless truth of the Bible relevant for everyday life.&nbsp;</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="color: rgb(0, 0, 0); ">The purpose of Timberwood Church is to honor God by making more disciples for Jesus Christ.</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><em style="color: rgb(105, 142, 179); font-weight: 600; ">We invite you to worship, serve, and celebrate with us!</em><br><span style="color: rgb(0, 0, 0); font-weight: 600; ">Sunday services are at 9 a.m. and 10:30 a.m.</span></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><br></p>'
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
                borderStyle: "none",
                fontFamily: "lato",
                fontFamilyType: "upload",
                fontSize: NaN,
                fontStyle: "",
                fontWeight: NaN,
                letterSpacing: NaN,
                lineHeight: 1.3,
                mobileFontSize: NaN,
                mobileFontStyle: "",
                mobileFontWeight: NaN,
                mobileLetterSpacing: NaN,
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontSize: NaN,
                tabletFontStyle: "",
                tabletFontWeight: NaN,
                tabletLetterSpacing: NaN,
                tabletLineHeight: 1.2,
                linkExternal:
                  "https://www.google.com/maps/d/edit?mid=1q-aNw687DnrS5vW9y0EE1SBnydw&usp=sharing",
                linkExternalBlank: "on",
                linkType: "external",
                text: "Visit our Prayer Path"
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
          horizontalAlign: undefined,
          items: [
            {
              type: "Button",
              value: {
                _id: "1",
                _styles: ["button"],
                borderStyle: "none",
                fontFamily: "lato",
                fontFamilyType: "upload",
                fontSize: NaN,
                fontStyle: "",
                fontWeight: NaN,
                letterSpacing: NaN,
                lineHeight: 1.3,
                mobileFontSize: NaN,
                mobileFontStyle: "",
                mobileFontWeight: NaN,
                mobileLetterSpacing: NaN,
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontSize: NaN,
                tabletFontStyle: "",
                tabletFontWeight: NaN,
                tabletLetterSpacing: NaN,
                tabletLineHeight: 1.2,
                linkExternal: "https://f4c1f8a9.churchtrac.com/connect",
                linkExternalBlank: "on",
                linkType: "external",
                text: "make a donation"
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
          horizontalAlign: undefined,
          items: [
            {
              type: "Button",
              value: {
                _id: "1",
                _styles: ["button"],
                borderStyle: "none",
                fontFamily: "lato",
                fontFamilyType: "upload",
                fontSize: NaN,
                fontStyle: "",
                fontWeight: NaN,
                letterSpacing: NaN,
                lineHeight: 1.3,
                linkExternal: "https://f4c1f8a9.churchtrac.com/connect",
                linkExternalBlank: "on",
                linkType: "external",
                mobileFontSize: NaN,
                mobileFontStyle: "",
                mobileFontWeight: NaN,
                mobileLetterSpacing: NaN,
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontSize: NaN,
                tabletFontStyle: "",
                tabletFontWeight: NaN,
                tabletLetterSpacing: NaN,
                tabletLineHeight: 1.2,
                text: "make a donation"
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>BROADCAST VIDEO</span></p><p class="brz-fs-lg-0 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Interested in joining this team?</span></p>'
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
                borderStyle: "none",
                fontFamily: "lato",
                fontFamilyType: "upload",
                fontSize: 1,
                fontStyle: "",
                fontWeight: NaN,
                letterSpacing: NaN,
                lineHeight: 1.3,
                linkExternal:
                  "https://forms.ministryforms.net/viewForm.aspx?formId=e6c65131-c32a-49ed-b8c6-e63634a7f0d2",
                linkExternalBlank: "on",
                linkType: "external",
                mobileFontSize: 1,
                mobileFontStyle: "",
                mobileFontWeight: NaN,
                mobileLetterSpacing: NaN,
                mobileLineHeight: 1.2,
                size: "custom",
                tabletFontSize: 1,
                tabletFontStyle: "",
                tabletFontWeight: NaN,
                tabletLetterSpacing: NaN,
                tabletLineHeight: 1.2,
                text: "CLICK HERE"
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
                text: '<p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>PRE-SCHOOL</span></p><p class="brz-fs-lg-0 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span style="">Interested In Joining This Team?</span><a data-location="external" data-button="true" data-detail="https://forms.ministryforms.net/viewForm.aspx?formId=10095747-3f27-49ab-a7a7-567fca858af3" data-category="link" target="_blank" role="button" style="background-color: rgb(255, 255, 255); " data-href="%7B%22type%22%3A%22external%22%2C%22anchor%22%3A%22%22%2C%22external%22%3A%22https%3A%2F%2Fforms.ministryforms.net%2FviewForm.aspx%3FformId%3D10095747-3f27-49ab-a7a7-567fca858af3%22%2C%22externalBlank%22%3A%22on%22%2C%22externalRel%22%3A%22off%22%2C%22externalType%22%3A%22external%22%2C%22population%22%3A%22%22%2C%22populationEntityId%22%3A%22%22%2C%22populationEntityType%22%3A%22%22%2C%22popup%22%3A%22%22%2C%22upload%22%3A%22%22%2C%22linkToSlide%22%3A1%2C%22internal%22%3A%22%22%2C%22internalBlank%22%3A%22off%22%2C%22pageTitle%22%3A%22%22%2C%22pageSource%22%3Anull%7D"><span style="background-color: rgb(255, 255, 255); ">CLICK HERE</span></a></p>'
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
                text: '<p class="brz-fs-lg-15 brz-ff-lato brz-ft-upload brz-fw-lg-600 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-0_4"><strong style="font-weight: 600; ">MONTHLY PRAYER GATHERING</strong><br></p><p class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-200 brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"></p><ul><li class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Have you ever struggled praying out loud in front of other people? Consider joining us for our monthly prayer meeting as we seek to learn, encourage, and grow deeper with God through prayer together.&nbsp;</span></li><li class="brz-fs-lg-1 brz-ff-lato brz-ft-upload brz-fw-lg-undefined brz-lh-lg-1_3 brz-lh-sm-1_2 brz-lh-xs-1_2 brz-text-lg-left brz-ls-lg-NaN_0"><span>Our prayer meetings happen monthly on the second Tuesday of every month at 6pm.</span></li></ul><p></p>'
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
        type: 'Wrapper',
        value: {
          _id: '1',
          _styles: ['wrapper', 'wrapper--richText'],
          items: [
            {
              type: 'RichText',
              value: {
                _id: '1',
                _styles: ['richText'],
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
  ex30
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
