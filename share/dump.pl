#!/usr/bin/perl -w

use strict;
use warnings;
use Monitoring::Livestatus;
use Data::Dumper;

my $ml = Monitoring::Livestatus->new(
        socket => '/var/lib/nagios3/live'
);
my $hosts = $ml->selectall_arrayref("GET hosts\nColumns: custom_variables\nLimit: 1", { Slice => {}});

print Dumper($hosts);
