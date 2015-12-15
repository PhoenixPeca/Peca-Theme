<?php if(!defined('KIRBY')) exit ?>

title: Site
pages: default
files:
  sortable: true
fields:

  siteOptions:
    label: Site Options
    type: headline
  title:
    label: Title
    type:  text
  author:
    label: Author
    type:  text
    icon: user
    width: 1/2
  email:
    label: Email
    type:  email
    width: 1/2
  description:
    label: Description
    type:  text
    icon: info-circle
  keywords:
    label: Keywords
    type:  tags

  siteSettings:
    label: Site Settings
    type: headline
  disqusSN:
    label: Disqus Shortname
    type:  text
  adult:
    label: Adult Content
    type: radio
    default: false
    options:
      true: Yes, this site contains adult contents.
      false: No, this site doesn't contain adult contents.
  simage:
    label: Smart Image
    type: radio
    default: false
    options:
      true: Yes, I want to enable Smart Image.
      false: No, I don't want to enable Smart Image.
  copyright:
    label: Copyright
    type:  textarea
