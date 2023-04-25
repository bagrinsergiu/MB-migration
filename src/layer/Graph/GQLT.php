<?php

namespace Brizy\layer\Graph;

class GQLT
{
    public function createPage_ql()
    {
        $template = 'mutation CreateCollectionItem($input: createCollectionItemInput!) {
              createCollectionItem(input: $input) {
                collectionItem {
                  ...CollectionItemFragment
                  __typename
                }
                __typename
              }
            }
            
            fragment CollectionItemFragment on CollectionItem {
              id
              title
              slug
              seo {
                title
                description
                enableIndexing
                __typename
              }
              social {
                image
                __typename
              }
              status
              visibility
              itemPassword
              pageData
              createdAt
              type {
                id
                slug
                title
                settings {
                  titleSingular
                  titlePlural
                  icon
                  __typename
                }
                ...CollectionTypeFieldsFragment
                __typename
              }
              ...CollectionItemFieldsFragment
              __typename
            }
            
            fragment CollectionTypeFieldsFragment on CollectionType {
              fields {
                id
                slug
                label
                type
                hidden
                priority
                required
                description
                placement
                ... on CollectionTypeFieldCheck {
                  checkSettings {
                    choices {
                      title
                      value
                      __typename
                    }
                    __typename
                  }
                  __typename
                }
                ... on CollectionTypeFieldColor {
                  settings
                  __typename
                }
                ... on CollectionTypeFieldDateTime {
                  settings
                  __typename
                }
                ... on CollectionTypeFieldEmail {
                  emailSettings {
                    placeholder
                    __typename
                  }
                  __typename
                }
                ... on CollectionTypeFieldFile {
                  settings
                  __typename
                }
                ... on CollectionTypeFieldGallery {
                  settings
                  __typename
                }
                ... on CollectionTypeFieldImage {
                  settings
                  __typename
                }
                ... on CollectionTypeFieldMap {
                  settings
                  __typename
                }
                ... on CollectionTypeFieldMultiReference {
                  multiReferenceSettings {
                    collectionType {
                      id
                      title
                      __typename
                    }
                    __typename
                  }
                  __typename
                }
                ... on CollectionTypeFieldNumber {
                  numberSettings {
                    min
                    max
                    step
                    placeholder
                    __typename
                  }
                  __typename
                }
                ... on CollectionTypeFieldMultiReference {
                  multiReferenceSettings {
                    collectionType {
                      id
                      title
                      __typename
                    }
                    __typename
                  }
                  __typename
                }
                ... on CollectionTypeFieldReference {
                  referenceSettings {
                    collectionType {
                      id
                      title
                      __typename
                    }
                    __typename
                  }
                  __typename
                }
                ... on CollectionTypeFieldRichText {
                  richTextSettings {
                    minLength
                    maxLength
                    placeholder
                    __typename
                  }
                  __typename
                }
                ... on CollectionTypeFieldSelect {
                  selectSettings {
                    choices {
                      title
                      value
                      __typename
                    }
                    placeholder
                    __typename
                  }
                  __typename
                }
                ... on CollectionTypeFieldSwitch {
                  settings
                  __typename
                }
                ... on CollectionTypeFieldLink {
                  linkSettings {
                    placeholder
                    __typename
                  }
                  __typename
                }
                ... on CollectionTypeFieldText {
                  textSettings {
                    minLength
                    maxLength
                    placeholder
                    __typename
                  }
                  __typename
                }
                __typename
              }
              __typename
            }
            
            fragment CollectionItemFieldsFragment on CollectionItem {
              fields {
                id
                type {
                  id
                  slug
                  type
                  __typename
                }
                ... on CollectionItemFieldCheck {
                  checkValues {
                    value
                    __typename
                  }
                  __typename
                }
                ... on CollectionItemFieldEmail {
                  emailValues {
                    value
                    __typename
                  }
                  __typename
                }
                ... on CollectionItemFieldImage {
                  imageValues {
                    id
                    focusPoint {
                      x
                      y
                      __typename
                    }
                    __typename
                  }
                  __typename
                }
                ... on CollectionItemFieldMultiReference {
                  multiReferenceValues {
                    collectionItems {
                      id
                      title
                      __typename
                    }
                    __typename
                  }
                  __typename
                }
                ... on CollectionItemFieldNumber {
                  numberValues {
                    value
                    __typename
                  }
                  __typename
                }
                ... on CollectionItemFieldReference {
                  referenceValues {
                    collectionItem {
                      id
                      title
                      __typename
                    }
                    __typename
                  }
                  __typename
                }
                ... on CollectionItemFieldRichText {
                  richTextValues {
                    value
                    __typename
                  }
                  __typename
                }
                ... on CollectionItemFieldSelect {
                  selectValues {
                    value
                    __typename
                  }
                  __typename
                }
                ... on CollectionItemFieldSwitch {
                  switchValues {
                    value
                    __typename
                  }
                  __typename
                }
                ... on CollectionItemFieldLink {
                  linkValues {
                    value
                    __typename
                  }
                  __typename
                }
                ... on CollectionItemFieldText {
                  textValues {
                    value
                    __typename
                  }
                  __typename
                }
                __typename
              }
              __typename
            }';
        return $template;
    }

    public function createNewBlock_ql()
    {
        $template = 'mutation UpdateCollectionItem($input: updateCollectionItemInput!) {
              updateCollectionItem(input: $input) {
                collectionItem {
                  id
                  title
                  slug
                  status
                  createdAt
                  pageData
                  type {
                    id
                    __typename
                  }
                  __typename
                }
                __typename
              }
            }
            ';
        return $template;
    }





}