ALTER TABLE `pl_corriculum_workplan_content_section_loads` ADD `_deleted` INT NOT NULL DEFAULT '0' , ADD INDEX (`_deleted`) ;
ALTER TABLE `pl_corriculum_workplan_content_section_load_topics` ADD `_deleted` INT NOT NULL DEFAULT '0' , ADD INDEX (`_deleted`) ;
ALTER TABLE `pl_corriculum_workplan_content_section_load_technologies` ADD `_deleted` INT NOT NULL DEFAULT '0' , ADD INDEX (`_deleted`) ;
ALTER TABLE `pl_corriculum_workplan_selfeducation` ADD `_deleted` INT NOT NULL DEFAULT '0' , ADD INDEX (`_deleted`) ;