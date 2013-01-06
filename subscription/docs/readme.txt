README FIRST
-----------------------

Thank you for trying the Subscription Module for XOOPS.

The framework for this module was created by Third Eye Software, inc. and has
been released as open source under the GPL(http://www.gnu.org/copyleft/gpl.html).

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

What is Subscription Module for XOOPS?
======================================
The subscription module allows you to create and manage subscriptions for your XOOPS
content.  When a user "purchases" a subscription, he or she is added to the
XOOPS security group that the subscription's type is assigned.

A subscription has a subsciption type and a subscription interval.  A
subscription type can have a parent Subscription type.  This is useful to
create a hierarchy of subscription types.  Currently, child subscription types
DO NOT inherit properties or behaviors from its parent.  This will be a feature
of a future release.

Supported XOOPS Version:
========================
The Subscription Module for XOOPS is intended for the 2.0.x branch of XOOPS.

Requirements:
=============
XOOPS 2.0.x

How do I get it?
================
Subscription Module for XOOPS is maintained by Third Eye Software, Inc and can
be found at http://products.thirdeyesoftware.com.

How do I install it?
====================
The Subscription Module for XOOPS is like any other module.  Decompress the
module archive in the modules subdirectory.  From the Modules Admin, select
'Subscription' module to install.

How do I use it?
================
1) Before you get started, it's useful to create the new groups that you will
assign your subscription types (in step 2) to.  Creating new groups is
accomplished from the Groups Admin section in the System Panel.

2) The first step in using the Subscription Module is to create the subscription
intervals.  Monthly and Yearly intervals are created by default.  You must
assign an interval type to each interval.  For instance, if you wanted a
monthly interval to renew every 30 days, you would choose the interval type
'Day' and the interval amount of '30'.  By default, the Monthly interval is set
up to renew every month (type=Month, amount=1).

3) Create or edit subscription types.  Basic and Premium subscription types are
created during installation.  These subscription types are used to relate a set
of subscriptions with the security groups the users will be assigned to if they
purchased a subscription.  If you have not created new security groups to
support the different access levels to your XOOPS site, you should do this
before continuing.

4) Create subscriptions with one of the types and intervals created in the
previous steps.  When a user purchases one of these subscriptions, he or she
will be automatically added to the security group assigned to the subscription
type for that subscription.

If you have any questions or require support, you may find some answers at
http://products.thirdeyesoftware.com.



