#!/usr/bin/perl -w

use strict;
use warnings;
use Monitoring::Livestatus;
use CGI;
use JSON qw(encode_json);
use Config::IniFiles;
use Data::Dumper;
use DBI;

#TODO: include config file

my $cgi = CGI->new;
print $cgi->header( "text/html" );

my $cfg_mysql = Config::IniFiles->new(-file => "../config/mysql.ini");

my $dbh = DBI->connect("DBI:mysql:database=".$cfg_mysql->val('mysql','db').";host=".$cfg_mysql->val('mysql','host').";port=".$cfg_mysql->val('mysql','port'), $cfg_mysql->val('mysql','user'), $cfg_mysql->val('mysql','pass')) || die "Unable to connect to database: "-DBI->errstr;
my $sth1 = $dbh->prepare('SELECT id, name, latitude, longlitude FROM location_plots WHERE name = ?') || die "Couldn't prepare statement: " . $dbh->errstr;

my $cfg = Config::IniFiles->new(-file => "../config/config.ini");
my $location_var = uc($cfg->val('locations','location_var'));
my $latitude_var = uc($cfg->val('locations','latitude_var'));
my $longlitude_var = uc($cfg->val('locations','longlitude_var'));

my $ml = Monitoring::Livestatus->new(
        socket => '/var/lib/nagios3/live'
);

my $refs = $ml->selectall_arrayref("GET hosts\nColumns: custom_variables", { Slice => {}});

my %res = ();
foreach (@{$refs}){
	if($_->{'custom_variables'}->{$location_var}){
		$sth1->execute($_->{'custom_variables'}->{$location_var});
		if($sth1->rows){
			my ($id, $name, $latitude, $longlitude) = $sth1->fetchrow_array();
			if($latitude != $_->{'custom_variables'}->{$latitude_var} || $longlitude != $_->{'custom_variables'}->{$longlitude_var}){
				$dbh->do("UPDATE location_plots SET latitude = ?, longlitude = ? WHERE id = ?", undef, ($_->{'custom_variables'}->{$latitude_var}, $_->{'custom_variables'}->{$longlitude_var}, $id));
			}
		}
		else{
			$dbh->do("INSERT INTO location_plots (name, latitude, longlitude) VALUES (?, ?, ?);", undef, ($_->{'custom_variables'}->{$location_var},$_->{'custom_variables'}->{$latitude_var},$_->{'custom_variables'}->{$longlitude_var}));
		}
		$sth1->finish;
	}
}

$dbh->disconnect;
print "{ret:1}";
