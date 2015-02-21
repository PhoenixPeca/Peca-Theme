<?php if(!defined('KIRBY')) exit ?>

title: Site
pages: default
fields:
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
    type:  textarea
  keywords:
    label: Keywords
    type:  tags
  adult:
    label: Adult Content
    type: radio
    default: false
    options:
      true: Yes, this site contains adult contents.
      false: No, this site doesn't contain adult contents.
  copyright:
    label: Copyright
    type:  textarea