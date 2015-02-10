<?php
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$sql[] = "CREATE TABLE mm_bundles (
id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
name VARCHAR(255) NOT NULL,
description TEXT NOT NULL,
is_free INT(11) NOT NULL,
status TINYINT(4) NOT NULL,
dflt_membership_id INT(11) UNSIGNED NOT NULL DEFAULT '0',
expire_amount INT(10) NULL DEFAULT NULL,	
expire_period ENUM('days','weeks','months') DEFAULT 'months',
expires TINYINT(4) NOT NULL DEFAULT '0',
short_name VARCHAR(10) NOT NULL,
PRIMARY KEY  (id)
);"; 

$sql[]="CREATE TABLE mm_log_api (
id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
request VARCHAR(355) NOT NULL ,
message TEXT NOT NULL ,
ipaddress VARCHAR(355) NOT NULL ,
date_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY  (id)
);"; 

$sql[]="CREATE TABLE mm_membership_levels (
id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
reference_key VARCHAR(6) NOT NULL,
name VARCHAR(250) UNIQUE NOT NULL,
is_free TINYINT NOT NULL DEFAULT '0',
is_default TINYINT(4) NOT NULL DEFAULT '0',
description TEXT,
wp_role VARCHAR(120) DEFAULT 'mm-ignore-role',
default_product_id INT(11) UNSIGNED DEFAULT NULL,
status TINYINT(4) NOT NULL,
email_subject TEXT NOT NULL,
email_body TEXT NOT NULL,
email_from_id BIGINT(20) UNSIGNED NOT NULL,
welcome_email_enabled TINYINT(4) DEFAULT '1',
expire_amount INT(10) NULL DEFAULT NULL,	
expire_period ENUM('days','weeks','months') DEFAULT 'months',
expires TINYINT(4) NOT NULL DEFAULT '0',
PRIMARY KEY  (id)
);"; 

$sql[] = "CREATE TABLE mm_membership_level_products (
id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
membership_id INT(11) UNSIGNED NOT NULL,
product_id INT(11) UNSIGNED NOT NULL,
PRIMARY KEY  (id)
);";

$sql[]="CREATE TABLE mm_membership_level_categories (
category_id BIGINT(20) UNSIGNED NOT NULL,
membership_level_id INT(11) UNSIGNED NOT NULL
);";

$sql[]="CREATE TABLE mm_bundle_products (
bundle_id INT(11) UNSIGNED NOT NULL,	
product_id INT(11) UNSIGNED NOT NULL
);";

$sql[]="CREATE TABLE mm_bundle_categories (
category_id BIGINT(20) UNSIGNED NOT NULL,
bundle_id INT(11) UNSIGNED NOT NULL
);";

$sql[]="CREATE TABLE mm_commission_profiles (
id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
name VARCHAR(250) UNIQUE NOT NULL,
is_default TINYINT(4) NOT NULL DEFAULT '0',
description TEXT,
initial_commission_enabled TINYINT(4) NOT NULL DEFAULT '1',
rebill_commissions_enabled TINYINT(4) NOT NULL DEFAULT '0',
rebill_commission_type enum('default','percent','flatrate') DEFAULT 'default',
rebill_commission_value decimal(20,2) NOT NULL,
do_limit_rebill_commissions TINYINT(4) NOT NULL DEFAULT '0',
rebill_commission_limit INT(11) UNSIGNED NOT NULL,
do_reverse_commissions TINYINT(4) NOT NULL DEFAULT '1',
PRIMARY KEY  (id)
);"; 

$sql[]="CREATE TABLE mm_posts_access (
post_id BIGINT(20) UNSIGNED NOT NULL,
access_type enum('member_type','access_tag') NOT NULL DEFAULT 'member_type',
access_id INT(11) UNSIGNED NOT NULL,
days char(5),
is_smart_content TINYINT NOT NULL DEFAULT '0',
KEY post_id (post_id),
KEY access_type (access_type),
KEY is_smart_content (is_smart_content),
KEY access_id (access_id)
);";

$sql[]="CREATE TABLE mm_smarttag_groups (
id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
parent_id BIGINT(20) UNSIGNED DEFAULT '0',
name VARCHAR(255) NOT NULL,
visible TINYINT(4) NOT NULL DEFAULT '1',
PRIMARY KEY  (id),
KEY parent_id (parent_id,visible)
);"; 

$sql[]="CREATE TABLE mm_smarttags (
id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
group_id INT(11) UNSIGNED NOT NULL,
name VARCHAR(255) NOT NULL,
visible TINYINT(4) NOT NULL DEFAULT '1',
PRIMARY KEY  (id),
KEY group_id (group_id,visible)
);"; 

$sql[]="CREATE TABLE mm_applied_bundles (
access_type enum('user','membership') NOT NULL DEFAULT 'membership',
access_type_id INT(11) UNSIGNED DEFAULT NULL,
bundle_id INT(11) UNSIGNED NOT NULL,
days_calc_method enum('join_date','custom_date','fixed') DEFAULT 'join_date',
days_calc_value VARCHAR(255),
status TINYINT(4) NOT NULL DEFAULT '1',
pending_status TINYINT(4) NOT NULL DEFAULT '0',
imported TINYINT(4) NOT NULL DEFAULT '0',
status_updated datetime DEFAULT NULL,
subscribed_provider_id INT(11) UNSIGNED DEFAULT NULL,
subscribed_list_id VARCHAR(255) DEFAULT NULL,
cancellation_date timestamp NULL DEFAULT 	NULL,
expiration_date timestamp NULL DEFAULT NULL,
apply_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
UNIQUE KEY unique_access_type (access_type,bundle_id,access_type_id),
KEY access_type (access_type),
KEY bundle_id (bundle_id)
);";

$sql[] = "CREATE TABLE mm_core_pages (
id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
page_id BIGINT(20) UNSIGNED NULL DEFAULT NULL,
core_page_type_id INT(11) UNSIGNED NOT NULL,
ref_type ENUM('member_type','error_type','access_tag','product') NULL DEFAULT NULL,
ref_id INT(11) UNSIGNED NULL DEFAULT NULL,
PRIMARY KEY  (id),
KEY core_page_idx1 (core_page_type_id,ref_type,ref_id,page_id)
);";

$sql[] = "CREATE TABLE mm_core_page_types (
id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
name VARCHAR(255) NOT NULL,
visible TINYINT NOT NULL DEFAULT '1',
PRIMARY KEY  (id)
);";

$sql[] = "CREATE TABLE mm_products (
id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
reference_key VARCHAR(6) NOT NULL,
status TINYINT(4) DEFAULT '1',
name VARCHAR(255) NOT NULL,
sku VARCHAR(255) NOT NULL,
description TEXT NOT NULL,
price decimal(20,4) NOT NULL,
currency CHAR(3) NOT NULL DEFAULT 'USD',
is_shippable TINYINT(4) NOT NULL,
has_trial TINYINT(4) NOT NULL,
trial_frequency enum('months','days','weeks','years') DEFAULT 'months',
rebill_period INT(11) NOT NULL,
rebill_frequency enum('months','days','weeks','years') DEFAULT 'months',
trial_amount decimal(20,2) NOT NULL,
trial_duration INT(11) DEFAULT NULL,
do_limit_payments TINYINT(4) DEFAULT '0',
number_of_payments INT(11) DEFAULT NULL,
last_modified timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
purchase_confirmation_message LONGTEXT NOT NULL,
commission_profile_id INT(11) DEFAULT '-1',
PRIMARY KEY  (id)
)";

$sql[] = "CREATE TABLE mm_employee_accounts (
id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
display_name VARCHAR(255) NOT NULL,
first_name VARCHAR(255),
last_name VARCHAR(255),
email VARCHAR(255) NOT NULL,
phone VARCHAR(255),
access_restrictions LONGTEXT NOT NULL,
role_id VARCHAR(255) NOT NULL,
user_id BIGINT(20) UNSIGNED NULL,
is_default TINYINT(4) NOT NULL,
PRIMARY KEY  (id)
);";

$sql[] = "CREATE TABLE mm_custom_fields (
id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
display_name VARCHAR(255) NOT NULL,
type VARCHAR(100) NOT NULL,
show_on_my_account TINYINT(4) DEFAULT '1',
is_hidden TINYINT(4) DEFAULT '0',
PRIMARY KEY  (id)
);";

$sql[] = "CREATE TABLE mm_custom_field_options (
id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
custom_field_id BIGINT(20) UNSIGNED NOT NULL,
value VARCHAR(255) NOT NULL,
PRIMARY KEY  (id)
);";

$sql[] = "CREATE TABLE mm_custom_field_data (
id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
custom_field_id BIGINT(20) UNSIGNED NOT NULL,
user_id BIGINT(20) UNSIGNED NOT NULL,
value TEXT NOT NULL,
date_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
last_updated TIMESTAMP NOT NULL,
PRIMARY KEY  (id),
KEY custom_field_id (custom_field_id)
);";

$sql[] = "CREATE TABLE mm_api_keys (
id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
name VARCHAR( 255 ) NOT NULL,
api_key VARCHAR( 255 ) NOT NULL,
api_secret VARCHAR( 255 ) NOT NULL,
status TINYINT(4) NOT NULL,
date_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY  (id)
);";

$sql[] = "CREATE TABLE mm_actions (
id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
event_type VARCHAR(255) NOT NULL,
action_type VARCHAR(255) NOT NULL,
action_value LONGTEXT NOT NULL,
event_attributes LONGTEXT NOT NULL,
status TINYINT(4) NOT NULL,
PRIMARY KEY  (id)
); ";

$sql[] = "CREATE TABLE mm_log_events (
id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
event_type VARCHAR(255) NULL,
ip VARCHAR(25) NOT NULL,
url VARCHAR(255) NOT NULL,
referrer VARCHAR(255) NOT NULL,
additional_params TEXT NOT NULL,
user_id BIGINT(20) UNSIGNED NOT NULL,
date_modified TIMESTAMP NULL,
date_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY  (id),
KEY event_user_id_idx (user_id),
KEY mm_log_events_idx1 (event_type,user_id,ip)
); ";

$sql[] = "CREATE TABLE mm_version_releases (
id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
version VARCHAR( 255 ) NOT NULL,
date_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
date_modified TIMESTAMP NULL DEFAULT NULL,
PRIMARY KEY  (id)
);";

$sql[] = "CREATE TABLE mm_orders (
id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
order_number VARCHAR(32) UNIQUE NOT NULL,
payment_id BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
user_id BIGINT(20) UNSIGNED NOT NULL,
affiliate_id VARCHAR(255) DEFAULT NULL,
sub_affiliate_id VARCHAR(255) DEFAULT NULL,
billing_first_name VARCHAR(32) DEFAULT NULL,
billing_last_name VARCHAR(32) DEFAULT NULL,
billing_phone VARCHAR(32) DEFAULT NULL,
billing_address1 VARCHAR(255) DEFAULT NULL,
billing_address2 VARCHAR(255) DEFAULT NULL,
billing_city VARCHAR(32) DEFAULT NULL,
billing_state VARCHAR(32) DEFAULT NULL,
billing_province VARCHAR(32) DEFAULT NULL,
billing_postal_code VARCHAR(16) DEFAULT NULL,
billing_country VARCHAR(2) DEFAULT NULL,
shipping_first_name VARCHAR(32) DEFAULT NULL,
shipping_last_name VARCHAR(32) DEFAULT NULL,
shipping_phone VARCHAR(32) DEFAULT NULL,
shipping_address1 VARCHAR(255) DEFAULT NULL,
shipping_address2 VARCHAR(255) DEFAULT NULL,
shipping_city VARCHAR(32) DEFAULT NULL,
shipping_state VARCHAR(32) DEFAULT NULL,
shipping_province VARCHAR(32) DEFAULT NULL,
shipping_postal_code VARCHAR(16) DEFAULT NULL,
shipping_country char(2) DEFAULT NULL,
shipping_option_key VARCHAR(255) DEFAULT NULL,
shipping_option_description VARCHAR(255) DEFAULT NULL,
subtotal decimal(19,4) DEFAULT NULL,
shipping decimal(19,4) DEFAULT NULL,
discount decimal(19,4) DEFAULT NULL,
tax decimal(19,4) DEFAULT NULL,
total decimal(19,4) DEFAULT NULL,
currency CHAR(3) NOT NULL DEFAULT 'USD',
status TINYINT(4) NOT NULL,
ip_address VARCHAR(64) DEFAULT NULL,
date_added timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
date_modified timestamp NULL DEFAULT NULL,
PRIMARY KEY  (id),
KEY order_user_id_idx (user_id)
)";

$sql[] = "CREATE TABLE mm_email_service_providers (
id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
provider_name VARCHAR(255) NOT NULL,
provider_token VARCHAR(255) NOT NULL,
username VARCHAR(255) NULL,
password VARCHAR(255) NULL,
api_key TEXT NULL,
additional_data TEXT NULL,
active SMALLINT UNSIGNED NOT NULL DEFAULT '0',
prospect_list_id VARCHAR(255) NULL,
cancellation_list_id VARCHAR(255) DEFAULT NULL,
PRIMARY KEY  (id),
UNIQUE KEY provider_token (provider_token)
);";

$sql[] = "CREATE TABLE mm_email_provider_mappings (
id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
member_type_id INT(11) UNSIGNED NOT NULL,
list_id VARCHAR(255) NOT NULL,
email_service_provider_id INT(11) UNSIGNED NOT NULL,
PRIMARY KEY  (id)
);";

$sql[] = "CREATE TABLE mm_email_provider_bundle_mappings (
id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
list_type VARCHAR(10) NOT NULL,
bundle_id INT(11) UNSIGNED NOT NULL,
list_id VARCHAR(255) NOT NULL,
email_service_provider_id INT(11) UNSIGNED NOT NULL,
PRIMARY KEY  (id)
);";

$sql[] = "CREATE TABLE mm_affiliate_providers (
id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
provider_name VARCHAR(255) NOT NULL,
provider_token VARCHAR(255) NOT NULL,
additional_data TEXT NULL,
active SMALLINT UNSIGNED NOT NULL DEFAULT '0',
PRIMARY KEY  (id),
UNIQUE KEY provider_token (provider_token)
);";

$sql[] = "CREATE TABLE mm_affiliate_provider_mappings (
id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
affiliate_provider_id INT(11) UNSIGNED NOT NULL,
membership_level_id INT(11) UNSIGNED NOT NULL,
payout_profile_id VARCHAR(255) NOT NULL,
additional_data TEXT NULL,
PRIMARY KEY  (id)
);";

$sql[] = "CREATE TABLE mm_affiliate_rebill_commissions (
affiliate_provider_id INT(11) UNSIGNED NOT NULL,
affiliate_id VARCHAR(255) NOT NULL,
order_number VARCHAR(32) NOT NULL,
transaction_id BIGINT(20) NOT NULL,
PRIMARY KEY  (affiliate_provider_id,affiliate_id,order_number,transaction_id)
);";

$sql[] = "CREATE TABLE mm_affiliate_partner_payouts (
id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
affiliate_id VARCHAR(255) NOT NULL,
product_id INT(11) NOT NULL,
commission_profile_id INT(11) NOT NULL,
PRIMARY KEY  (id)
);";

$sql[] = "CREATE TABLE mm_coupon_usage (
id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
coupon_id INT(11) UNSIGNED NOT NULL,
user_id BIGINT(20) UNSIGNED NOT NULL,
product_id INT(11) UNSIGNED NOT NULL,
product_order_item_id BIGINT(20) UNSIGNED NOT NULL,
date_modified TIMESTAMP NULL DEFAULT NULL,
date_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY  (id),
KEY coupon_usage_coupon_id_idx (coupon_id)
);";

$sql[] = "CREATE TABLE mm_coupon_restrictions (
id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
coupon_id INT(11) UNSIGNED NOT NULL,
product_id INT(11) UNSIGNED NOT NULL,
date_modified TIMESTAMP NULL DEFAULT NULL,
date_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY  (id)
);";

$sql[] = "CREATE TABLE mm_coupons (
id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
coupon_name VARCHAR(50) NOT NULL,
coupon_code VARCHAR(50) NOT NULL,
coupon_type ENUM('percentage','dollar','free') DEFAULT 'percentage',
coupon_value DECIMAL(19,4) NOT NULL,
coupon_value_currency CHAR(3) NULL,
description TEXT NULL,
quantity INT NOT NULL DEFAULT '0',
start_date TIMESTAMP NULL DEFAULT NULL,
end_date TIMESTAMP NULL DEFAULT NULL,
recurring_billing_setting ENUM ('all','first') DEFAULT 'all',
is_gift smallint(1) NOT NULL DEFAULT 0,
gift_user_id BIGINT(20) UNSIGNED NOT NULL,
gift_order_item_id BIGINT(20) UNSIGNED NOT NULL,
date_modified TIMESTAMP NULL DEFAULT NULL,
date_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY  (id),
KEY coupons_coupon_code_idx (coupon_code),
KEY coupons_start_date_end_date_idx (start_date,end_date),
KEY coupons_gift_user_id_idx (gift_user_id),
KEY coupons_gift_order_item_id_idx (gift_order_item_id)
);";

$sql[] = "CREATE TABLE mm_transaction_key (
id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
transaction_key VARCHAR(32) NOT NULL UNIQUE,
user_id BIGINT(20) UNSIGNED NOT NULL,
order_id BIGINT(20) UNSIGNED NOT NULL,
age DATETIME NOT NULL,
PRIMARY KEY  (id)
);";

$sql[] = "CREATE TABLE mm_login_token (
id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
login_token VARCHAR(32) NOT NULL UNIQUE,
user_id BIGINT(20) UNSIGNED NOT NULL,
age DATETIME NOT NULL,
PRIMARY KEY  (id)
);";

$sql[] = "CREATE TABLE mm_countries (
iso CHAR(2) NOT NULL,
name VARCHAR(80) NOT NULL,
printable_name VARCHAR(80) NOT NULL,
iso3 CHAR(3),
numcode SMALLINT,
PRIMARY KEY  (iso)
);";

$sql[] = "CREATE TABLE mm_card_on_file (
id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
user_id BIGINT(20) UNSIGNED NOT NULL,
payment_service_id INT(11) NOT NULL,
payment_service_identifier VARCHAR(255) NOT NULL,
original_order_id BIGINT(20) UNSIGNED DEFAULT NULL,
PRIMARY KEY  (id),
KEY card_on_file_user_id_idx (user_id)
)";

$sql[] = "CREATE TABLE mm_order_items (
id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
order_id BIGINT(20) UNSIGNED NOT NULL,
item_type smallint(6) NOT NULL,
description VARCHAR(255) NOT NULL,
amount decimal(19,4) NOT NULL,
currency CHAR(3) NOT NULL DEFAULT 'USD',
quantity INT(11) NOT NULL,
total decimal(19,4) NOT NULL,
item_id INT(11) UNSIGNED DEFAULT NULL,
status smallint(6) NOT NULL DEFAULT 0,
is_recurring smallint(1) NOT NULL DEFAULT 0,
recurring_amount decimal(19,4) DEFAULT NULL,
recurring_discount decimal(19,4) DEFAULT NULL,
trial_amount decimal(19,4) DEFAULT NULL,
trial_frequency enum('months','days','weeks','years') DEFAULT NULL,
trial_duration INT(11) DEFAULT NULL,
rebill_period INT(11) DEFAULT NULL,
rebill_frequency enum('months','days','weeks','years') DEFAULT NULL,
max_rebills INT(11) DEFAULT NULL,
is_gift smallint(1) NOT NULL DEFAULT 0,
PRIMARY KEY  (id),
KEY order_items_order_id_idx (order_id),
KEY item_type_item_id_idx (item_type,item_id)
)";

$sql[]="CREATE TABLE mm_order_item_access (
order_item_id BIGINT(20) UNSIGNED NOT NULL,
user_id BIGINT(20) UNSIGNED NOT NULL,
access_type enum('membership','bundle') NOT NULL DEFAULT 'membership',
access_type_id INT(11) NOT NULL,
KEY order_item_access_order_item_id_idx (order_item_id),
KEY order_item_access_user_id_idx (user_id)
);";

$sql[] = "CREATE TABLE mm_payment_services (
id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
token VARCHAR(64) UNIQUE NOT NULL,
name VARCHAR(255) NOT NULL,
settings LONGTEXT NOT NULL,
active smallint(6) NOT NULL,
PRIMARY KEY  (id)
)";

$sql[] = "CREATE TABLE mm_shipping_methods (
id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
token VARCHAR(64) UNIQUE NOT NULL,
name VARCHAR(255) NOT NULL,
settings LONGTEXT NOT NULL,
active smallint(6) NOT NULL,
PRIMARY KEY  (id)
)";

$sql[] = "CREATE TABLE mm_transaction_log (
id BIGINT(20) NOT NULL AUTO_INCREMENT,
order_id BIGINT(20) UNSIGNED DEFAULT NULL,
order_item_id BIGINT(20) UNSIGNED NOT NULL,
amount decimal(19,4) NOT NULL,
currency CHAR(3) NOT NULL DEFAULT 'USD',
description VARCHAR(255) DEFAULT NULL,
payment_service_id INT(11) UNSIGNED DEFAULT NULL,
payment_service_detail_id BIGINT(20) UNSIGNED DEFAULT NULL,
transaction_type INT(11) UNSIGNED NOT NULL,
transaction_date datetime NOT NULL,
refund_id BIGINT(20) NULL,
PRIMARY KEY  (id),
KEY transaction_type_idx (transaction_type,transaction_date),
KEY order_order_item_id (order_id,order_item_id),
KEY payment_service_detail_lookup_idx (payment_service_id,payment_service_detail_id)
)";

$sql[]="CREATE TABLE mm_user_data (
wp_user_id BIGINT(20) NOT NULL,
membership_level_id INT(11) UNSIGNED NOT NULL,
password VARCHAR(255) DEFAULT NULL,
status TINYINT(4) NOT NULL,
pending_status TINYINT(4) NOT NULL DEFAULT '0',
imported TINYINT(4) NOT NULL DEFAULT '0',
status_message VARCHAR(255) DEFAULT NULL,
days_calc_method enum('join_date','custom_date','fixed') NOT NULL DEFAULT 'join_date',
days_calc_value varchar(255) DEFAULT NULL,
notes text,
first_name varchar(255) DEFAULT NULL,
last_name varchar(255) DEFAULT NULL,
phone varchar(32) DEFAULT NULL,
billing_address1 varchar(255) DEFAULT NULL,
billing_address2 varchar(255) DEFAULT NULL,
billing_city varchar(32) DEFAULT NULL,
billing_state varchar(32) DEFAULT NULL,
billing_province varchar(32) DEFAULT NULL,
billing_postal_code varchar(16) DEFAULT NULL,
billing_country varchar(2) DEFAULT NULL,
shipping_address1 varchar(255) DEFAULT NULL,
shipping_address2 varchar(255) DEFAULT NULL,
shipping_city varchar(32) DEFAULT NULL,
shipping_state varchar(32) DEFAULT NULL,
shipping_province varchar(32) DEFAULT NULL,
shipping_postal_code varchar(16) DEFAULT NULL,
shipping_country varchar(2) DEFAULT NULL,
subscribed_provider_id int(11) unsigned DEFAULT NULL,
subscribed_list_id varchar(255) DEFAULT NULL,
origin_affiliate_id varchar(255) NOT NULL DEFAULT '',
origin_subaffiliate_id varchar(255) NOT NULL DEFAULT '',
became_active timestamp NULL DEFAULT NULL,
welcome_email_sent timestamp NULL DEFAULT NULL,
last_login_date timestamp NULL DEFAULT NULL,
status_updated timestamp NULL DEFAULT NULL,
cancellation_date timestamp NULL DEFAULT NULL,
expiration_date timestamp NULL DEFAULT NULL,
last_updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY  (wp_user_id),
KEY mm_user_data_mls_lookup (membership_level_id,status),
KEY mm_user_data_statdate_idx (status,status_updated)
);";

$sql[] = "CREATE TABLE mm_flatrate_shipping_options (
id BIGINT(20) NOT NULL AUTO_INCREMENT,
option_name VARCHAR(255) NOT NULL,
rate decimal(19,4) NOT NULL,
currency CHAR(3) NOT NULL DEFAULT 'USD',
PRIMARY KEY  (id)
)";

$sql[] = "CREATE TABLE mm_scheduled_events (
id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
event_type TINYINT(3) UNSIGNED NOT NULL,
event_data TEXT NOT NULL,
scheduled_date DATETIME NOT NULL,
processed_date DATETIME,
status TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
PRIMARY KEY  (id),
KEY mm_scheduler_event_type_idx (event_type),
KEY mm_scheduler_scheduled_date_idx (scheduled_date),
KEY mm_scheduler_status_idx (status)
)";

$sql[] = "CREATE TABLE mm_scheduled_payments (
id BIGINT(20) UNSIGNED NOT NULL,
user_id BIGINT(20) UNSIGNED NOT NULL,
order_item_id BIGINT(20) UNSIGNED NOT NULL,
payment_service_id INT(11) UNSIGNED NOT NULL,
PRIMARY KEY  (id),
KEY scheduled_payment_oiu_lookup_idx (order_item_id,user_id)
)";

$sql[] = "CREATE TABLE mm_queued_scheduled_events (
event_id BIGINT(20) UNSIGNED NOT NULL,
command TINYINT(3) UNSIGNED NOT NULL,
queued_date DATETIME NOT NULL,
batch_id VARCHAR(32) DEFAULT NULL,
batch_started DATETIME DEFAULT NULL,
PRIMARY KEY  (event_id),
KEY queued_event_command_type_lookup_idx (command,queued_date),
KEY queued_event_batch_id_lookup_idx (batch_id)
)";

$sql[] = "CREATE TABLE mm_report_data_cache (
id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
query_target varchar(256) NOT NULL,
query_token varchar(256) NOT NULL,
query_params MEDIUMTEXT NOT NULL,
param_hash VARCHAR(32) NOT NULL,
query_data LONGTEXT DEFAULT NULL,
status VARCHAR(32) NOT NULL,
expiration_date DATETIME NOT NULL,
PRIMARY KEY  (id),
UNIQUE KEY mm_report_data_cache_token_hash_idx (query_target(64),query_token(64),param_hash(32)),
KEY mm_report_data_cache_date_idx (expiration_date)
)";

$sql[] = "CREATE TABLE mm_social_login_providers (
id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
name VARCHAR(128) NOT NULL,
token VARCHAR(64) UNIQUE NOT NULL,
active TINYINT(1) DEFAULT '0',
api_key VARCHAR(255) DEFAULT NULL,
api_secret VARCHAR(255) DEFAULT NULL,
allow_signups TINYINT DEFAULT '1',
signup_membership_level BIGINT(20) DEFAULT '0',
settings LONGTEXT NOT NULL,
PRIMARY KEY  (id)
)";

$sql[] = "CREATE TABLE mm_social_login_linked_profiles (
id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
provider_id BIGINT NOT NULL,
unique_id VARCHAR(255) NOT NULL,
user_id BIGINT(20) NOT NULL,
removable TINYINT(1) DEFAULT '1',
PRIMARY KEY  (id),
UNIQUE KEY social_login_unique_id_lookup_idx (provider_id,unique_id)
)";

$sql[] = "CREATE TABLE mm_diagnostic_log (
id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
type VARCHAR(32) NOT NULL,
ip_address VARCHAR(64) DEFAULT NULL,
session VARCHAR(32) DEFAULT NULL,
location VARCHAR(255) NULL,
line INT(11) NULL,
event TEXT NOT NULL,
event_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY  (id)
); ";
?>