#!/usr/bin/perl -w

use strict;
use warnings;
use Monitoring::Livestatus;
use Data::Dumper;

my $ml = Monitoring::Livestatus->new(
        socket => '/var/lib/nagios3/live'
);

#my $hosts = $ml->selectall_arrayref("GET hosts",{Slice => {}});
#print Dumper(@{$hosts});
#<>;
#my $services = $ml->selectall_arrayref("GET services",{Slice => {}});
#print Dumper($services);
#<>;
#my $hostgroups = $ml->selectall_arrayref("GET hostgroups",{Slice => {}});
#print Dumper($hostgroups);
<>;
my $servicegroups  = $ml->selectall_arrayref("GET servicegroups",{Slice => {}});
print Dumper($servicegroups);

