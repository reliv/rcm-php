-- MySQL dump 10.13  Distrib 5.5.24, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: wespresslocal
-- ------------------------------------------------------
-- Server version	5.5.24-0ubuntu0.12.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `rcm_countries`
--

DROP TABLE IF EXISTS `rcm_countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rcm_countries` (
  `iso3` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `iso2` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `countryName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`iso3`),
  UNIQUE KEY `UNIQ_E220C84C1B6F9774` (`iso2`),
  UNIQUE KEY `UNIQ_E220C84C8852FE53` (`countryName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rcm_countries`
--

LOCK TABLES `rcm_countries` WRITE;
/*!40000 ALTER TABLE `rcm_countries` DISABLE KEYS */;
INSERT INTO `rcm_countries` VALUES ('ABW','AW','Aruba'),('AFG','AF','Afghanistan'),('AGO','AO','Angola'),('AIA','AI','Anguilla'),('ALB','AL','Albania'),('AND','AD','Andorra'),('ANT','AN','Netherlands Antilles'),('ARE','AE','United Arab Emirates'),('ARG','AR','Argentina'),('ARM','AM','Armenia'),('ASM','AS','American Samoa'),('ATA','AQ','Antarctica'),('ATF','TF','French Southern Territories'),('ATG','AG','Antigua and Barbuda'),('AUS','AU','Australia'),('AUT','AT','Austria'),('AZE','AZ','Azerbaijan'),('BDI','BI','Burundi'),('BEL','BE','Belgium'),('BEN','BJ','Benin'),('BFA','BF','Burkina Faso'),('BGD','BD','Bangladesh'),('BGR','BG','Bulgaria'),('BHR','BH','Bahrain'),('BHS','BS','Bahamas'),('BIH','BA','Bosnia and Herzegovina'),('BLR','BY','Belarus'),('BLZ','BZ','Belize'),('BMU','BM','Bermuda'),('BOL','BO','Bolivia, Plurinational State of'),('BRA','BR','Brazil'),('BRB','BB','Barbados'),('BRN','BN','Brunei Darussalam'),('BTN','BT','Bhutan'),('BVT','BV','Bouvet Island'),('BWA','BW','Botswana'),('CAF','CF','Central African Republic'),('CAN','CA','Canada'),('CCK','CC','Cocos (Keeling) Islands'),('CHE','CH','Switzerland'),('CHL','CL','Chile'),('CHN','CN','China'),('CIV','CI','CÃ´te d\'Ivoire'),('CMR','CM','Cameroon'),('COD','CD','Congo, the Democratic Republic of the'),('COG','CG','Congo'),('COK','CK','Cook Islands'),('COL','CO','Colombia'),('COM','KM','Comoros'),('CPV','CV','Cape Verde'),('CRI','CR','Costa Rica'),('CUB','CU','Cuba'),('CXR','CX','Christmas Island'),('CYM','KY','Cayman Islands'),('CYP','CY','Cyprus'),('CZE','CZ','Czech Republic'),('DEU','DE','Germany'),('DJI','DJ','Djibouti'),('DMA','DM','Dominica'),('DNK','DK','Denmark'),('DOM','DO','Dominican Republic'),('DZA','DZ','Algeria'),('ECU','EC','Ecuador'),('EGY','EG','Egypt'),('ERI','ER','Eritrea'),('ESH','EH','Western Sahara'),('ESP','ES','Spain'),('EST','EE','Estonia'),('ETH','ET','Ethiopia'),('FIN','FI','Finland'),('FJI','FJ','Fiji'),('FLK','FK','Falkland Islands (Malvinas)'),('FRA','FR','France'),('FRO','FO','Faroe Islands'),('FSM','FM','Micronesia, Federated States of'),('GAB','GA','Gabon'),('GBR','GB','United Kingdom'),('GEO','GE','Georgia'),('GGY','GG','Guernsey'),('GHA','GH','Ghana'),('GIB','GI','Gibraltar'),('GIN','GN','Guinea'),('GLP','GP','Guadeloupe'),('GMB','GM','Gambia'),('GNB','GW','Guinea-Bissau'),('GNQ','GQ','Equatorial Guinea'),('GRC','GR','Greece'),('GRD','GD','Grenada'),('GRL','GL','Greenland'),('GTM','GT','Guatemala'),('GUF','GF','French Guiana'),('GUM','GU','Guam'),('GUY','GY','Guyana'),('HKG','HK','Hong Kong'),('HMD','HM','Heard Island and McDonald Islands'),('HND','HN','Honduras'),('HRV','HR','Croatia'),('HTI','HT','Haiti'),('HUN','HU','Hungary'),('IDN','ID','Indonesia'),('IMN','IM','Isle of Man'),('IND','IN','India'),('IOT','IO','British Indian Ocean Territory'),('IRL','IE','Ireland'),('IRN','IR','Iran, Islamic Republic of'),('IRQ','IQ','Iraq'),('ISL','IS','Iceland'),('ISR','IL','Israel'),('ITA','IT','Italy'),('JAM','JM','Jamaica'),('JEY','JE','Jersey'),('JOR','JO','Jordan'),('JPN','JP','Japan'),('KAZ','KZ','Kazakhstan'),('KEN','KE','Kenya'),('KGZ','KG','Kyrgyzstan'),('KHM','KH','Cambodia'),('KIR','KI','Kiribati'),('KNA','KN','Saint Kitts and Nevis'),('KOR','KR','Korea, Republic of'),('KWT','KW','Kuwait'),('LAO','LA','Lao People\'s Democratic Republic'),('LBN','LB','Lebanon'),('LBR','LR','Liberia'),('LBY','LY','Libyan Arab Jamahiriya'),('LCA','LC','Saint Lucia'),('LIE','LI','Liechtenstein'),('LKA','LK','Sri Lanka'),('LSO','LS','Lesotho'),('LTU','LT','Lithuania'),('LUX','LU','Luxembourg'),('LVA','LV','Latvia'),('MAC','MO','Macao'),('MAR','MA','Morocco'),('MCO','MC','Monaco'),('MDA','MD','Moldova, Republic of'),('MDG','MG','Madagascar'),('MDV','MV','Maldives'),('MEX','MX','Mexico'),('MHL','MH','Marshall Islands'),('MKD','MK','Macedonia, the former Yugoslav Republic of'),('MLI','ML','Mali'),('MLT','MT','Malta'),('MMR','MM','Myanmar'),('MNE','ME','Montenegro'),('MNG','MN','Mongolia'),('MNP','MP','Northern Mariana Islands'),('MOZ','MZ','Mozambique'),('MRT','MR','Mauritania'),('MSR','MS','Montserrat'),('MTQ','MQ','Martinique'),('MUS','MU','Mauritius'),('MWI','MW','Malawi'),('MYS','MY','Malaysia'),('MYT','YT','Mayotte'),('NAM','NA','Namibia'),('NCL','NC','New Caledonia'),('NER','NE','Niger'),('NFK','NF','Norfolk Island'),('NGA','NG','Nigeria'),('NIC','NI','Nicaragua'),('NIU','NU','Niue'),('NLD','NL','Netherlands'),('NOR','NO','Norway'),('NPL','NP','Nepal'),('NRU','NR','Nauru'),('NZL','NZ','New Zealand'),('OMN','OM','Oman'),('PAK','PK','Pakistan'),('PAN','PA','Panama'),('PCN','PN','Pitcairn'),('PER','PE','Peru'),('PHL','PH','Philippines'),('PLW','PW','Palau'),('PNG','PG','Papua New Guinea'),('POL','PL','Poland'),('PRI','PR','Puerto Rico'),('PRK','KP','Korea, Democratic People\'s Republic of'),('PRT','PT','Portugal'),('PRY','PY','Paraguay'),('PSE','PS','Palestinian Territory, Occupied'),('PYF','PF','French Polynesia'),('QAT','QA','Qatar'),('REU','RE','RÃ©union'),('ROU','RO','Romania'),('RUS','RU','Russian Federation'),('RWA','RW','Rwanda'),('SAU','SA','Saudi Arabia'),('SDN','SD','Sudan'),('SEN','SN','Senegal'),('SGP','SG','Singapore'),('SGS','GS','South Georgia and the South Sandwich Islands'),('SHN','SH','Saint Helena, Ascension and Tristan da Cunha'),('SJM','SJ','Svalbard and Jan Mayen'),('SLB','SB','Solomon Islands'),('SLE','SL','Sierra Leone'),('SLV','SV','El Salvador'),('SMR','SM','San Marino'),('SOM','SO','Somalia'),('SPM','PM','Saint Pierre and Miquelon'),('SRB','RS','Serbia'),('STP','ST','Sao Tome and Principe'),('SUR','SR','Suriname'),('SVK','SK','Slovakia'),('SVN','SI','Slovenia'),('SWE','SE','Sweden'),('SWZ','SZ','Swaziland'),('SYC','SC','Seychelles'),('SYR','SY','Syrian Arab Republic'),('TCA','TC','Turks and Caicos Islands'),('TCD','TD','Chad'),('TGO','TG','Togo'),('THA','TH','Thailand'),('TJK','TJ','Tajikistan'),('TKL','TK','Tokelau'),('TKM','TM','Turkmenistan'),('TLS','TL','Timor-Leste'),('TON','TO','Tonga'),('TTO','TT','Trinidad and Tobago'),('TUN','TN','Tunisia'),('TUR','TR','Turkey'),('TUV','TV','Tuvalu'),('TWN','TW','Taiwan, Province of China'),('TZA','TZ','Tanzania, United Republic of'),('UGA','UG','Uganda'),('UKR','UA','Ukraine'),('UMI','UM','United States Minor Outlying Islands'),('URY','UY','Uruguay'),('USA','US','United States'),('UZB','UZ','Uzbekistan'),('VAT','VA','Holy See (Vatican City State)'),('VCT','VC','Saint Vincent and the Grenadines'),('VEN','VE','Venezuela, Bolivarian Republic of'),('VGB','VG','Virgin Islands, British'),('VIR','VI','Virgin Islands, U.S.'),('VNM','VN','Viet Nam'),('VUT','VU','Vanuatu'),('WLF','WF','Wallis and Futuna'),('WSM','WS','Samoa'),('YEM','YE','Yemen'),('ZAF','ZA','South Africa'),('ZMB','ZM','Zambia'),('ZWE','ZW','Zimbabwe');
/*!40000 ALTER TABLE `rcm_countries` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-09-28 14:28:23
