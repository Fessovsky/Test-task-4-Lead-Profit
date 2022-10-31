<?php
//  ** Знания MySQL + оптимизировать запросы **
// 
//  Имеется 3 таблицы: info, data, link, есть запрос для получения данных:
//  select * from data,link,info where link.info_id = info.id and link.data_id = data.id
//  предложить варианты оптимизации: 
//

// Первое, что бросается в глаза это название колонки desc, это команда сортировки, думаю будет конфликт и mysql, что не даст создать таблицу.
// В таблице link можно добавить id'шники в PRIMARY KEY
// Можно оптимизировать длину имени до 35. 
<<<T
CREATE TABLE `info` (
        `id` int(11) NOT NULL auto_increment,
       `name` varchar(255) default NULL,
        `desc` text default NULL,
        PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

CREATE TABLE `data` (
        `id` int(11) NOT NULL auto_increment,
        `date` date default NULL,
        `value` INT(11) default NULL,
        PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


CREATE TABLE `link` (
        `data_id` int(11) NOT NULL,
        `info_id` int(11) NOT NULL
         // PRIMARY KEY (`data_id`, `info_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

T;

// Что касается запроса, думаю, лучше сделать inner join "SELECT * FROM `data` JOIN link ON link.data_id = `data`.id JOIN info ON link.info_id = info.id;" 