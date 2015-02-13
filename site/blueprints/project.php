<?php if(!defined('KIRBY')) exit ?>

title: Article
pages: false
files:
  sortable: true
fields:
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
    type: user
    width: 1/2
  description:
    label: Description
    type:  textarea
  keywords:
    label: Keywords
    type:  tags
  tags:
    label: Tags
    type:  tags
  text:
    label: Text
    type:  textarea