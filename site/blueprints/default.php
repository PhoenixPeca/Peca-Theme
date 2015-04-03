<?php if(!defined('KIRBY')) exit ?>

title: Page
pages: true
files:
  sortable: true
fields:

  pageMeta:
    label: Page Meta
    type: headline
  title:
    label: Title
    type:  text
  description:
    label: Description
    type:  text
    icon: info-circle
  keywords:
    label: Keywords
    type:  tags

  pageContent:
    label: Page Content
    type: headline
  text:
    label: Text
    type:  textarea