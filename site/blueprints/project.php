<?php if(!defined('KIRBY')) exit ?>

title: Article
pages: false
files:
  sortable: true
fields:

  pageOptions:
    label: Page Options
    type: headline
  title:
    label: Title
    type:  text
  date:
    label: Date
    type: date
    width: 1/2
    default: today
  author:
    label: Author
    type:  text
    icon: user
    width: 1/2
  description:
    label: Description
    type:  text
    icon: info-circle
  keywords:
    label: Keywords
    type:  tags
  tags:
    label: Tags
    type:  tags
  comments:
    label: Comments & Discussions
    type: radio
    default: true
    options:
      true: Yes, allow users to comment on this page.
      false: No, don't allow users to comment on this page.

  pageContent:
    label: Page Content
    type: headline
  text:
    label: Text
    type:  textarea