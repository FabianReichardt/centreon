-- 
--  Creation and config of central broker
--

INSERT INTO `cfg_centreonbroker` (`config_id`, `config_name`, `config_filename`, `config_write_timestamp`,`config_activate`, `ns_nagios_server`) VALUES (1,'central-broker-master','central-broker.xml','1','1',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'name','central-broker-master','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'port','5669','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'buffering_timeout','','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'host','','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'failover','','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'retry_interval','','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'protocol','ndo','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'tls','no','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'private_key','','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'public_cert','','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'ca_certificate','','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'compression','no','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'compression_level','','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'compression_buffer','','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'type','ipv4','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'blockId','2_3','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'name','@CENTREONBROKER_LOG@/central-broker-master.log','logger',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'config','yes','logger',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'debug','no','logger',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'error','yes','logger',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'info','no','logger',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'level','low','logger',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'type','file','logger',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'blockId','3_17','logger',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'name','central-broker-sql-master','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'db_type','mysql','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'retry_interval','','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'buffering_timeout','','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'failover','central-broker-sql-master-failover','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'db_host','@DB_HOST@','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'db_port','@DB_PORT@','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'db_user','@DB_USER@','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'db_password','@DB_PASS@','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'db_name','@STORAGE_DB@','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'queries_per_transaction','','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'read_timeout','','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'type','sql','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'blockId','1_16','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'name','centreon-broker-rrd-master','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'port','5670','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'buffering_timeout','','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'host','@DB_HOST@','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'failover','centreon-broker-rrd-master-failover','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'retry_interval','','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'protocol','ndo','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'tls','no','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'private_key','','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'public_cert','','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'ca_certificate','','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'compression','no','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'compression_level','','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'compression_buffer','','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'type','ipv4','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'blockId','1_3','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'name','central-broker-perfdata-master','output',3);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'interval','60','output',3);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'failover','central-broker-perfdata-master-failover','output',3);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'retry_interval','','output',3);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'buffering_timeout','','output',3);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'length','15552000','output',3);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'db_type','mysql','output',3);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'db_host','@DB_HOST@','output',3);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'db_port','@DB_PORT@','output',3);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'db_user','@DB_USER@','output',3);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'db_password','@DB_PASS@','output',3);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'db_name','@STORAGE_DB@','output',3);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'queries_per_transaction','','output',3);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'read_timeout','','output',3);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'type','storage','output',3);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'blockId','1_14','output',3);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'name','central-broker-sql-master-failover','output',4);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'path','@CENTREONBROKER_VARLIB@/central-broker-sql-master.retention','output',4);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'buffering_timeout','','output',4);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'protocol','ndo','output',4);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'retry_interval','','output',4);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'failover','','output',4);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'compression','no','output',4);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'compression_level','','output',4);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'compression_buffer','','output',4);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'type','file','output',4);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'blockId','1_11','output',4);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'name','centreon-broker-rrd-master-failover','output',5);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'path','@CENTREONBROKER_VARLIB@/centreon-broker-rrd-master.retention','output',5);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'buffering_timeout','','output',5);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'protocol','ndo','output',5);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'retry_interval','','output',5);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'failover','','output',5);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'compression','no','output',5);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'compression_level','','output',5);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'compression_buffer','','output',5);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'type','file','output',5);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'blockId','1_11','output',5);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'name','central-broker-perfdata-master-failover','output',6);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'path','@CENTREONBROKER_VARLIB@/central-broker-perfdata-master.retention','output',6);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'buffering_timeout','','output',6);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'protocol','ndo','output',6);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'retry_interval','','output',6);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'failover','','output',6);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'compression','no','output',6);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'compression_level','','output',6);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'compression_buffer','','output',6);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'type','file','output',6);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'blockId','1_11','output',6);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'name','central-broker-stat-master','stats',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'fifo','@CENTREONBROKER_VARLIB@/central-broker-stat-master.pipe','stats',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'type','stats','stats',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'blockId','5_23','stats',1);

-- Temporary
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'name','Broker-temporary','temporary',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'path','@CENTREONBROKER_VARLIB@/broker-temporary.tmp','temporary',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'protocol','ndo','temporary',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'compression','no','temporary',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'compression_level','','temporary',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'compression_buffer','','temporary',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'max_size','','temporary',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'type','file','temporary',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (1,'blockId','6_25','temporary',1);


-- 
--  Creation and config of central rrd
--

INSERT INTO `cfg_centreonbroker` (`config_id`, `config_name`, `config_filename`, `config_write_timestamp`, `config_activate`, `ns_nagios_server`) VALUES (2,'central-rrd-master','central-rrd.xml','1','1',1);

INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'name','central-rrd-master','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'port','5670','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'buffering_timeout','','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'host','','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'failover','','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'retry_interval','','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'protocol','ndo','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'tls','no','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'private_key','','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'public_cert','','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'ca_certificate','','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'compression','no','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'compression_level','','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'compression_buffer','','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'type','ipv4','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'blockId','2_3','input',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'name','@CENTREONBROKER_LOG@/central-master-rrd.log','logger',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'config','yes','logger',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'debug','no','logger',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'error','yes','logger',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'info','no','logger',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'level','low','logger',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'type','file','logger',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'blockId','3_17','logger',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'name','central-rrd-output-master','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'metrics_path','@CENTREON_VARLIB@/metrics','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'status_path','@CENTREON_VARLIB@/status','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'failover','central-rrd-output-master-failover','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'retry_interval','','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'buffering_timeout','','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'path','','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'port','','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'type','rrd','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'blockId','1_13','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'name','central-rrd-output-master-failover','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'path','@CENTREONBROKER_VARLIB@/central-rrd-master.retention','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'buffering_timeout','','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'protocol','ndo','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'retry_interval','','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'failover','','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'compression','no','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'compression_level','','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'compression_buffer','','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'type','file','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'blockId','1_11','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'name','central-rrd-stat-master','stats',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'fifo','@CENTREONBROKER_VARLIB@/central-rrd-stat-master.pipe','stats',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'type','stats','stats',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'blockId','5_23','stats',1);

-- Temporary
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'name','RRD-temporary','temporary',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'path','@CENTREONBROKER_VARLIB@/rrdtool-temporary.tmp','temporary',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'protocol','ndo','temporary',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'compression','no','temporary',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'compression_level','','temporary',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'compression_buffer','','temporary',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'max_size','','temporary',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'type','file','temporary',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (2,'blockId','6_25','temporary',1);

--
--  Creation and config of central module
--

INSERT INTO `cfg_centreonbroker` (`config_id`, `config_name`, `config_filename`, `config_write_timestamp`, `config_activate`, `ns_nagios_server`) VALUES (3,'central-module-master','central-module.xml','1','1',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'name','@CENTREONBROKER_LOG@/central-module-master.log','logger',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'config','yes','logger',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'debug','no','logger',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'error','yes','logger',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'info','no','logger',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'level','low','logger',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'type','file','logger',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'blockId','3_17','logger',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'name','central-module-output-master','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'port','5669','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'host','@DB_HOST@','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'failover','central-module-output-master-failover','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'retry_interval','','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'buffering_timeout','','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'protocol','ndo','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'tls','no','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'private_key','','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'public_cert','','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'ca_certificate','','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'compression','no','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'compression_level','','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'compression_buffer','','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'type','ipv4','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'blockId','1_3','output',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'name','central-module-output-master-failover','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'path','@MONITORING_VAR_LIB@/central-module-master.retention','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'failover','','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'retry_interval','','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'protocol','ndo','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'buffering_timeout','','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'compression','no','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'compression_level','','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'compression_buffer','','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'type','file','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'blockId','1_11','output',2);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'name','central-module-stat-master','stats',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'fifo','@CENTREONBROKER_VARLIB@/central-module-stat-master.pipe','stats',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'type','stats','stats',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'blockId','5_23','stats',1);

-- Temporary
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'name','Module-temporary','temporary',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'path','@CENTREONBROKER_VARLIB@/module-temporary.tmp','temporary',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'protocol','ndo','temporary',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'compression','no','temporary',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'compression_level','','temporary',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'compression_buffer','','temporary',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'max_size','','temporary',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'type','file','temporary',1);
INSERT INTO `cfg_centreonbroker_info` (`config_id`, `config_key`, `config_value`, `config_group`, `config_group_id`) VALUES (3,'blockId','6_25','temporary',1);


INSERT INTO `options` (`key`, `value`) VALUES ('broker', 'broker');
INSERT INTO `options` (`key`, `value`) VALUES ('centstorage', '0');
INSERT INTO `options` (`key`, `value`) VALUES ('broker_correlator_script', '@BROKER_INIT_SCRIPT@');

INSERT INTO `options` (`key`, `value`) VALUES ('enable_perfdata_sync', '0');
INSERT INTO `options` (`key`, `value`) VALUES ('enable_logs_sync', '0');

UPDATE `nagios_server` SET `centreonbroker_cfg_path` = '@CENTREONBROKER_ETC@' WHERE `id` = 1;
UPDATE `nagios_server` SET `centreonbroker_module_path` = '@CENTREONBROKER_LIB@' WHERE `id` = 1;

INSERT INTO cfg_nagios_broker_module (`cfg_nagios_id`, `broker_module`) VALUES (1, '@CENTREONBROKER_CBMOD@ @CENTREONBROKER_ETC@/central-module.xml');
