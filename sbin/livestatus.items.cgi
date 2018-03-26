#!/usr/bin/perl -w

use strict;
use warnings;
use Monitoring::Livestatus;
use CGI;
use JSON qw(encode_json);
use Data::Dumper;

my $cgi = CGI->new;
print $cgi->header( "text/html" );

my $ml = Monitoring::Livestatus->new(
        socket => '/var/lib/nagios3/live'
);

my $hosts = $ml->selectcol_arrayref("GET hosts\nColumns: name");
my $services = $ml->selectcol_arrayref("GET services\nColumns: description");
my $hostgroups = $ml->selectcol_arrayref("GET hostgroups\nColumns: name");
my $servicegroups  = $ml->selectcol_arrayref("GET servicegroups\nColumns: name");

print encode_json({hosts=>$hosts,services=>[uniq(@$services)],hostgroups=>$hostgroups,servicegroups=>$servicegroups});

sub uniq{
	my %s;
	return grep { !$s{$_}++ } @_;
}
