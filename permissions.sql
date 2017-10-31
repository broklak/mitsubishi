insert into `permissions` set display_name='View Dashboard', name='read.dashboard'

insert into `permissions` set display_name='Update Server Secret Key', name='update.serverkey'

insert into `permissions` set display_name='Update All SPK', name='update-all.spk'

insert into `permissions` set display_name='Update Dealer SPK', name='update-dealer.spk'

ALTER TABLE approval_setting CHANGE job_position_id role_id int(11);