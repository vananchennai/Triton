-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 29, 2015 at 07:10 AM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ssvdemo`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `r_dwcstock`(IN fmdate DATE,IN todate DATE,IN fvalues Varchar(10000),IN fnvalues Varchar(10000))
BEGIN
DECLARE monthy DATE;
DECLARE monthyear VARCHAR(15);
SET monthyear =  DATE_FORMAT(fmdate,'%Y-%m');
SET @sql = NULL;
set session group_concat_max_len = 40096;

  SET @Tqry = CONCAT('SELECT GROUP_CONCAT( DISTINCT CONCAT(''SUM(CASE WHEN franchisename = '''''', DM.FName,'''''' THEN `StockonHand` ELSE 0 END) AS `'', DM.FName,''`'')
  )INTO @sql
FROM  (SELECT DISTINCT Franchisename  AS FName FROM `franchisemaster` where ',fnvalues,' ORDER BY Franchisename) DM');
PREPARE stmt FROM @Tqry;
EXECUTE stmt;
DROP PREPARE stmt;


SET @qry = CONCAT('SELECT productcode,productdes,',@sql,' FROM( SELECT
stockoutput.franchiseecode AS franchisecode,
vr.Franchisename as franchisename,
stockoutput.pc AS productcode,
pt.ProductDescription AS productdes,
SUM(stockoutput.openstock+stockoutput.receipt) - SUM(stockoutput.issue) AS StockonHand
FROM(
SELECT franchiseecode,
       productcode as pc,
       0 AS openstock,
       receipt,
       issue
FROM stock_day
WHERE stockdate BETWEEN ''',fmdate,''' AND ''',todate,'''

UNION ALL

SELECT franchiseecode,
       productcode as pc,
       openstock,
       0 AS receipt,
       0 AS issue
FROM stock_month
WHERE (stockdate =''',monthyear,'''))stockoutput
LEFT JOIN view_rbrs vr ON vr.Franchisecode = stockoutput.franchiseecode
LEFT JOIN view_rptproductfin pt ON pt.ProductCode = stockoutput.pc
WHERE ',fvalues,'
group by stockoutput.franchiseecode,stockoutput.pc)stest group by productcode');
PREPARE stmt FROM @qry;
EXECUTE stmt;
DROP PREPARE stmt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `r_dwreport`(IN fmdate DATE,IN todate DATE,IN fvalues Varchar(10000),IN fnvalues Varchar(10000))
BEGIN
SET @sql = NULL;


  SET @Tqry = CONCAT('SELECT GROUP_CONCAT( DISTINCT CONCAT(''SUM(CASE WHEN franchisename = '''''', DM.FName,'''''' THEN `quantity` ELSE 0 END) AS `'', DM.FName,''`'')
  )INTO @sql
FROM  (SELECT DISTINCT Franchisename  AS FName FROM `franchisemaster` where ',fnvalues,' ORDER BY Franchisename) DM');


	PREPARE stmt FROM @Tqry;

	EXECUTE stmt;

	DROP PREPARE stmt;



  SET @qry = CONCAT('SELECT `ptypename` AS `Product Type`,',@sql,',SUM(`quantity`) AS `Total Net Sale Qty`

  FROM `r_salesreport`

  WHERE `salesdates` BETWEEN ''',fmdate,''' AND ''',todate,''' AND ',fvalues,'

  GROUP BY `ptypename`

  ORDER BY ptypename');


	PREPARE stmt FROM @qry;

	EXECUTE stmt;

	DROP PREPARE stmt;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `r_iwssreport`(IN fmdate DATE,IN todate DATE,IN fvalues Varchar(10000),IN fnvalues Varchar(10000))
BEGIN
SET @sql = NULL;
set session group_concat_max_len = 40096;

  SET @Tqry = CONCAT('SELECT GROUP_CONCAT( DISTINCT CONCAT(''SUM(CASE WHEN franchisename = '''''', DM.FName,'''''' THEN `quantity` ELSE 0 END) AS `'', DM.FName,''`'')
  )INTO @sql
FROM  (SELECT DISTINCT Franchisename  AS FName FROM `franchisemaster` where ',fnvalues,' ORDER BY Franchisename) DM');


	PREPARE stmt FROM @Tqry;

	EXECUTE stmt;

	DROP PREPARE stmt;



  SET @qry = CONCAT('SELECT `productcode` AS `Product Code`,',@sql,',SUM(`quantity`) AS `Total Net Sale Qty`

  FROM `r_salesreport`

  WHERE `salesdates` BETWEEN ''',fmdate,''' AND ''',todate,''' AND ',fvalues,'

  GROUP BY `productcode`

  ORDER BY productcode');


	PREPARE stmt FROM @qry;

	EXECUTE stmt;

	DROP PREPARE stmt;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `r_MWSreport`(IN fmdate DATE,IN todate DATE,IN fvalues Varchar(10000))
BEGIN
DECLARE sqry VARCHAR(100000);

SET @sql = NULL;
SELECT
    GROUP_CONCAT( DISTINCT CONCAT(' SUM(CASE WHEN DATE_FORMAT(`salesdate`,''%b_%Y'') = ''', DM.MYear,''' THEN `actualqty` ELSE 0 END) AS ', DM.MYear)
  ) INTO @sql
FROM  (SELECT DISTINCT DATE_FORMAT(`salesdate`,'%m'),DATE_FORMAT(`salesdate`,'%b_%Y') AS MYear FROM `retailersales`
           WHERE ((`salesdate` >= fmdate) AND (`salesdate` <= todate)) ORDER BY 1 ) DM;


SET sqry =CONCAT('SELECT ptypename as `Product Type Name`, productcode as `Product Code`,',@sql,',sum(`actualqty`) as Totals FROM(
SELECT
`rs`.`regionname` AS `regionname`,
`rs`.`branchname` AS `branchname`,
`rs`.`franchisename` AS `franchisename`,
`rs`.`retailername` AS `retailername`,
`rs`.`salesno` AS `salesno`,
`rs`.`salesdates` AS `salesdate`,
`rs`.`VoucherType` AS `vtype`,
`rs`.`productcode` AS `productcode`,
`rs`.`pgroupname` AS `pgroupname`,
`rs`.`psegmentname` AS `psegmentname`,
`rs`.`ptypename` AS `ptypename`,
`rm`.`Category` AS `rcategory`,
`rm`.`retailerclassification` AS `rclass`,
`rm`.`franchiseeme` AS `franchiseeme`,
`rm`.`geographical` AS `geographical`,
`rm`.`retailercategory1` AS `rc1`,
`rm`.`retailercategory2` AS `rc2`,
`rm`.`retailercategory3` AS `rc3`,
`rs`.`quantity` AS `actualqty`
FROM  `r_salesreport` `rs`
LEFT JOIN `retailermaster` `rm` ON `rm`.`RetailerName` = `rs`.`retailername`
AND (SELECT SPLIT_STR(`rm`.`RetailerCode`, ''-'', 1) = `rs`.`franchisecode`)
WHERE `rs`.`salesdates` BETWEEN ''fmdate'' AND ''todate''
GROUP BY `rs`.`productcode`,`rs`.`franchisename`,`rs`.`regionname`,`rs`.`branchname`,`rs`.`VoucherType`,`rs`.`unique_id`)rcdrep
WHERE QUERYCON GROUP BY ptypename,productcode ORDER BY ptypename,productcode  ASC ');
SET sqry = REPLACE(sqry,'QUERYCON',fvalues);
SET sqry = REPLACE(sqry,'fmdate',fmdate);
SET @qry = REPLACE(sqry,'todate',todate);
PREPARE stmt FROM @qry;
EXECUTE stmt;
DROP PREPARE stmt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `r_pprreport`(IN fmdate DATE,IN todate DATE,IN fvalues Varchar(10000))
BEGIN
SET @qry = CONCAT('SELECT franchisecode AS `Franchisee Code`, franchisename AS `Franchisee Name`,
productcode AS `Product Code`, SUM(IFNULL(`Pqty`,0)) AS `Purchase Qty`,
SUM(IFNULL(`prqty`,0)) AS `Purchase Return Qty` FROM(
SELECT r.`franchisecode`, r.`franchisename`, r.`productcode`, r.`quantity` AS Pqty, pr.`quantity` AS prqty
FROM r_purchasereport r
LEFT JOIN r_purchasereturn pr ON r.`franchisecode`= pr.`franchisecode` AND r.`productcode`=pr.`productcode` AND pr.`purchaseRetdate` BETWEEN ''',fmdate,''' AND ''',todate,'''
WHERE r.`purchasedate` BETWEEN ''',fmdate,''' AND ''',todate,'''
)prreport
WHERE ',fvalues,'
GROUP BY `franchisecode`,`productcode`');

	PREPARE stmt FROM @qry;

	EXECUTE stmt;

	DROP PREPARE stmt;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `r_purchaseorder`(IN fmdate DATE,IN todate DATE,IN fvalues Varchar(10000))
BEGIN
	SET @qry = CONCAT('SELECT r.`RegionName`,r.`branchname`,r.`FranchiseCode`,r.`Franchisename`,r.`PurchaseOrderNo`,
  r.`PurchaseOrderDate`,r.`pgroupname`,r.`psegmentname`,r.`ptypename`,r.`ProductCode`,r.`ProductDescription`,IFNULL(SUM(r.OrderQty),0) AS `OrderQty`,
  p.purchasenumber AS GRNNo,p.purchasedate AS GRNDate,IFNULL(SUM(p.quantity),0) AS ReceivedQty,IFNULL(SUM(r.OrderQty),0) - IFNULL(SUM(p.quantity),0) AS PendingQty
FROM r_purchaseorder r
LEFT JOIN r_purchasereport p
ON p.PO=r.PurchaseOrderNo AND p.`franchisecode`= r.`FranchiseCode` AND p.productcode=r.ProductCode AND p.PO!=''''
WHERE (r.`PurchaseOrderDate` BETWEEN ''',fmdate,''' AND ''',todate,''') AND ',fvalues,'
GROUP BY r.`PurchaseOrderNo`,r.`FranchiseCode`,r.ProductCode
ORDER BY r.`PurchaseOrderDate` ASC,r.`FranchiseCode`');
PREPARE stmt FROM @qry;
EXECUTE stmt;
DROP PREPARE stmt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `r_purchasereport`(IN fmdate DATE,IN todate DATE,IN fvalues Varchar(10000))
BEGIN
	SET @qry = CONCAT('SELECT regionname, branchname, franchisecode, franchisename, purchasenumber, purchasedate,
  PO, pgroupname, psegmentname, ptypename, productcode, vouchertype, quantity, NetAmount,
  taxamount, grossamt
  FROM r_purchasereport
	WHERE `purchasedate` BETWEEN ''',fmdate,''' AND ''',todate,''' AND ',fvalues,'
	ORDER BY `purchasedate` ASC,franchisecode,vouchertype,productcode');
PREPARE stmt FROM @qry;
EXECUTE stmt;
DROP PREPARE stmt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `r_purchasereturns`(IN fmdate DATE,IN todate DATE,IN fvalues Varchar(10000))
BEGIN
	SET @qry = CONCAT('SELECT regionname,branchname,franchisecode,franchisename,purchaseRetnumber,purchaseRetdate,productcode,pgroupname,psegmentname,ptypename,vouchertype,quantity,NetAmount,taxamount,grossamt
	FROM r_purchasereturn
	WHERE `purchaseRetdate` BETWEEN ''',fmdate,''' AND ''',todate,''' AND ',fvalues,'
	ORDER BY `purchaseRetdate` ASC,franchisecode,vouchertype,productcode');
PREPARE stmt FROM @qry;
EXECUTE stmt;
DROP PREPARE stmt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `r_purchasesummary`(IN fmdate DATE,IN todate DATE,IN fvalues Varchar(10000))
BEGIN
	SET @qry = CONCAT('SELECT	`regionname`,`branchname`,`franchisecode`,`franchisename`,`pgroupname`,`psegmentname`,
  `ptypename`,`productcode`,`vouchertype`,SUM(`quantity`) AS `quantity`,ROUND(SUM(IFNULL(`NetAmount`,0))) AS `NetAmount`,
  ROUND(SUM(IFNULL(`taxamount`,0))) AS `taxamount`,ROUND(SUM(IFNULL(`grossamt`,0))) AS `grossamt`
  FROM `r_purchasereport`
  WHERE `purchasedate` BETWEEN ''',fmdate,''' AND ''',todate,''' AND ',fvalues,'
  GROUP BY `franchisecode`,`productcode`,`vouchertype`
  ORDER BY franchisecode');
	PREPARE stmt FROM @qry;
	EXECUTE stmt;
	DROP PREPARE stmt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `r_RCDreport`(IN fmdate DATE,IN todate DATE,IN fvalues Varchar(100000))
BEGIN
DECLARE sqry VARCHAR(1000000);
 SET sqry ='SELECT * FROM(SELECT
`rs`.`regionname` AS `regionname`,
`rs`.`branchname` AS `branchname`,
`rs`.`franchisename` AS `franchisename`,
`rs`.`retailername` AS `retailername`,
`rs`.`salesno` AS `salesno`,
`rs`.`salesdates` AS `salesdate`,
`rs`.`VoucherType` AS `vtype`,
`rs`.`productcode` AS `productcode`,
`rs`.`pgroupname` AS `pgroupname`,
`rs`.`psegmentname` AS `psegmentname`,
`rs`.`ptypename` AS `ptypename`,
`rm`.`Category` AS `rcategory`,
`rm`.`retailerclassification` AS `rclass`,
`rm`.`franchiseeme` AS `franchiseeme`,
`rm`.`geographical` AS `geographical`,
`rm`.`retailercategory1` AS `rc1`,
`rm`.`retailercategory2` AS `rc2`,
`rm`.`retailercategory3` AS `rc3`,
`rs`.`quantity` AS `actualqty`
FROM  `r_salesreport` `rs`
LEFT JOIN `retailermaster` `rm` ON `rm`.`RetailerName` = `rs`.`retailername`
AND (SELECT SPLIT_STR(`rm`.`RetailerCode`, ''-'', 1) = `rs`.`franchisecode`)
WHERE `rs`.`salesdates` BETWEEN ''fmdate'' AND ''todate''
GROUP BY `rs`.`productcode`,`rs`.`franchisename`,`rs`.`regionname`,`rs`.`branchname`,`rs`.`VoucherType`,`rs`.`unique_id`
)rcdrep WHERE QUERYCON ORDER BY salesdate ASC';
SET sqry = REPLACE(sqry,'QUERYCON',fvalues);
SET sqry = REPLACE(sqry,'fmdate',fmdate);
SET @qry = REPLACE(sqry,'todate',todate);
PREPARE stmt FROM @qry;
EXECUTE stmt;
DROP PREPARE stmt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `r_salereturns`(IN fmdate DATE,IN todate DATE,IN fvalues Varchar(10000))
BEGIN
	SET @qry = CONCAT('SELECT regionname, branchname, franchisecode, franchisename, salesRetno, salesRetdates,
  retailername, pgroupname, psegmentname, ptypename, productcode, productdes, VoucherType, quantity, amount, TaxAmount, grossamt
	FROM r_salesreturn
	WHERE `salesRetdates` BETWEEN ''',fmdate,''' AND ''',todate,''' AND ',fvalues,'
	ORDER BY `salesRetdates` ASC,franchisecode,VoucherType,productcode');
PREPARE stmt FROM @qry;
EXECUTE stmt;
DROP PREPARE stmt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `r_salesregister`(IN fmdate DATE,IN todate DATE,IN fvalues Varchar(10000))
BEGIN
	SET @qry = CONCAT('SELECT	`regionname`,`branchname`,`franchisecode`,`franchisename`,`productcode`,`pgroupname`,`psegmentname`,
  `ptypename`,`VoucherType`,SUM(`quantity`) AS `quantity`,ROUND(SUM(IFNULL(`amount`,0))) AS `netamount`,
  ROUND(SUM(IFNULL(`TaxAmount`,0))) AS `taxamount`,ROUND(SUM(IFNULL(`grossamt`,0))) AS `grossamt`
  FROM `r_salesreport`
  WHERE `salesdates` BETWEEN ''',fmdate,''' AND ''',todate,''' AND ',fvalues,'
  GROUP BY `franchisecode`,`productcode`,`VoucherType`
  ORDER BY franchisecode');
	PREPARE stmt FROM @qry;
	EXECUTE stmt;
	DROP PREPARE stmt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `r_salesreport`(IN fmdate DATE,IN todate DATE,IN fvalues Varchar(10000))
BEGIN
	SET @qry = CONCAT('SELECT `regionname`, `branchname`, `franchisecode`, `franchisename`, `salesno`, `salesdates`,
	`retailername`, `productcode`, `productdes`, `pgroupname`, `psegmentname`, `ptypename`, `VoucherType`,
	`quantity`, `amount`, `TaxAmount`, `grossamt`
	FROM r_salesreport
	WHERE `salesdates` BETWEEN ''',fmdate,''' AND ''',todate,''' AND ',fvalues,'
	ORDER BY `salesdates` ASC,franchisecode,VoucherType,salesno,productcode');
PREPARE stmt FROM @qry;
EXECUTE stmt;
DROP PREPARE stmt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `r_spwswsreport`(IN fmdate DATE,IN todate DATE,IN fvalues Varchar(10000),IN fnvalues Varchar(10000))
BEGIN
SET @sql = NULL;


  SET @Tqry = CONCAT('SELECT GROUP_CONCAT( DISTINCT CONCAT(''SUM(CASE WHEN franchisename = '''''', DM.FName,'''''' THEN `quantity` ELSE 0 END) AS `'', DM.FName,''`'')
  )INTO @sql
FROM  (SELECT DISTINCT Franchisename  AS FName FROM `franchisemaster` where ',fnvalues,' ORDER BY Franchisename) DM');


	PREPARE stmt FROM @Tqry;

	EXECUTE stmt;

	DROP PREPARE stmt;



  SET @qry = CONCAT('SELECT `psegmentname` AS `Sub Product Description`,',@sql,',SUM(`quantity`) AS `Total Net Sale Qty`

  FROM `r_salesreport`

  WHERE `salesdates` BETWEEN ''',fmdate,''' AND ''',todate,''' AND ',fvalues,'

  GROUP BY `psegmentname`

  ORDER BY psegmentname');


	PREPARE stmt FROM @qry;

	EXECUTE stmt;

	DROP PREPARE stmt;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `r_ssrreport`(IN fmdate DATE,IN todate DATE,IN fvalues Varchar(10000))
BEGIN
SET @qry = CONCAT('SELECT franchisecode AS `Franchisee Code`, franchisename AS `Franchisee Name`,
productcode AS `Product Code`, SUM(IFNULL(`Pqty`,0)) AS `Sales Qty`,
SUM(IFNULL(`prqty`,0)) AS `Sales Return Qty` FROM(
SELECT r.`franchisecode`, r.`franchisename`, r.`productcode`, r.`quantity` AS Pqty, pr.`quantity` AS prqty
FROM r_salesreport r
LEFT JOIN r_salesreturn pr ON r.`franchisecode`= pr.`franchisecode` AND r.`productcode`=pr.`productcode` AND pr.`salesRetdates` BETWEEN ''',fmdate,''' AND ''',todate,'''
WHERE r.`salesdates` BETWEEN ''',fmdate,''' AND ''',todate,'''
)prreport
WHERE ',fvalues,'
GROUP BY `franchisecode`,`productcode`');

	PREPARE stmt FROM @qry;

	EXECUTE stmt;

	DROP PREPARE stmt;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `r_sswpwssreport`(IN fmdate DATE,IN todate DATE,IN fvalues Varchar(10000),IN Ptvalues Varchar(10000))
BEGIN

SET @sql = NULL;
set session group_concat_max_len = 40090;

  SET @Tqry = CONCAT('SELECT GROUP_CONCAT( DISTINCT CONCAT(''SUM(CASE WHEN ptypename = '''''', DM.FName,'''''' THEN `quantity` ELSE 0 END) AS `'', DM.FName,''`'')
  )INTO @sql
FROM  (SELECT DISTINCT ProductTypeName  AS FName FROM `producttypemaster` where ',Ptvalues,' ORDER BY ProductTypeName) DM');


	PREPARE stmt FROM @Tqry;

	EXECUTE stmt;

	DROP PREPARE stmt;


  SET @qry = CONCAT('SELECT `franchisename` AS `Super Stockist`,`productdes` AS `Item Description`,',@sql,',SUM(`quantity`) AS `Total Net Sale Quantity`

  FROM `r_salesreport`

  WHERE `salesdates` BETWEEN ''',fmdate,''' AND ''',todate,''' AND ',fvalues,'

  GROUP BY `productdes`

  ORDER BY productdes');

	PREPARE stmt FROM @qry;

	EXECUTE stmt;

	DROP PREPARE stmt;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `r_wmsalesreport`(IN wfdate DATE,IN mfdate DATE,IN todate DATE,IN fvalues Varchar(10000))
BEGIN
DECLARE sqry VARCHAR(100000);
 SET sqry ='SELECT
regionname AS `RegionName`,
branchname,
franchisecode AS `Franchisecode`,
franchisename AS `Franchisename`,
pgroupname,
psegmentname,
ptypename,
productcode,
SUM(CASE WHEN ((`preicelevel` = ''Consumer Price'') and (`salesdates` between ''wfdate'' and ''todate'')) then
`quantity`
ELSE 0
END) AS `wconsumerqty`,
SUM(CASE WHEN (`preicelevel` = ''Consumer Price'') then `quantity` ELSE 0 END) AS `mconsumerqty`,
SUM(CASE WHEN ((`preicelevel` = ''Retailer'') and (`salesdates` between ''wfdate'' and ''todate'')) then
`quantity`
ELSE 0
END) AS `wretailerqty`,
SUM(CASE WHEN (`preicelevel` = ''Retailer'') then `quantity` ELSE 0 END) AS `mretailerqty`,
SUM(CASE WHEN ((`preicelevel` = ''Consumer Price'' or `preicelevel` = ''Retailer'') and (`salesdates` between ''wfdate'' and ''todate'')) then
`quantity`
ELSE 0
END) AS `totalweeksales`,
SUM(CASE WHEN (`preicelevel` = ''Consumer Price'' or `preicelevel` = ''Retailer'') then `quantity` ELSE 0 END) AS `totalmonthlysales`
FROM  r_salesreport
WHERE ((salesdates >= ''mfdate'') and (salesdates <= ''todate'') and (preicelevel in (''Consumer Price'',''Retailer''))) AND QUERYCON
GROUP BY `productcode`,`franchisecode`';
SET sqry = REPLACE(sqry,'QUERYCON',fvalues);
SET sqry = REPLACE(sqry,'wfdate',wfdate);
SET sqry = REPLACE(sqry,'mfdate',mfdate);
SET @qry = REPLACE(sqry,'todate',todate);        
PREPARE stmt FROM @qry;
EXECUTE stmt;
DROP PREPARE stmt;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `r_zsssreport`(IN fmdate DATE,IN todate DATE,IN fvalues Varchar(10000))
BEGIN
SET @qry = CONCAT('SELECT regionname AS `Region Name`,statename AS `State Name`,branchname AS `Branch Name`,qty AS `Net Sale Qty`
FROM (SELECT r.regionname,v.statename,v.branchname,SUM(r.quantity) AS qty FROM r_salesreport r
LEFT JOIN view_rbrs v ON r.franchisecode = v.Franchisecode
WHERE `salesdates` BETWEEN ''',fmdate,''' AND ''',todate,''' AND ',fvalues,'
GROUP BY r.regionname,v.statename,v.branchname)zssales');


	PREPARE stmt FROM @qry;

	EXECUTE stmt;

	DROP PREPARE stmt;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `salesreportnew`()
BEGIN
DECLARE SUAUTO double;
DECLARE sqry VARCHAR(100000);
set @SUAUTO=0;
Truncate r_salesreport;
SET sqry ='Insert into r_salesreport (regionname, branchname, franchisecode, franchisename, salesno, salesdates, retailername, productcode, productdes, pgroupname, psegmentname, ptypename, VoucherType, quantity, amount, TaxAmount, grossamt, voucherstatus, preicelevel, unique_id)SELECT regionname,branchname,franchisecode,franchisename,salesno,salesdates,retailername,productcode,productdes,pgroupname,psegmentname,ptypename,VoucherType,quantity,amount,TaxAmount,grossamt,voucherstatus, preicelevel, unique_id FROM(
SELECT
`re`.`RegionName` AS `regionname`,
`b`.`branchname` AS `branchname`,
`r`.`franchisecode` AS `franchisecode`,
`f`.`Franchisename` AS `franchisename`,
`rs`.`salesno` AS `salesno`,
`rs`.`salesdate` AS `salesdates`,
`rs`.`retailername` AS `retailername`,
`r`.`productcode` AS `productcode`,
`p`.`ProductDescription` AS `productdes`,
`pgm`.`ProductGroup` AS `pgroupname`,
`psm`.`ProductSegment` AS `psegmentname`,
`ptm`.`ProductTypeName` AS `ptypename`,
`rs`.`VoucherType` AS `VoucherType`,
sum(`r`.`quantity`) AS `quantity`,
round(sum(ifnull(`r`.`amount`,0))) AS `amount`,
round(sum(ifnull(`r`.`taxvalue`,0))) AS `TaxAmount`,
(round(sum(ifnull(`r`.`taxvalue`,0))) + round(sum(ifnull(`r`.`amount`,0)))) AS `grossamt`,
`rs`.`voucherstatus` AS `voucherstatus`,
`rs`.`pricelevel` AS `preicelevel`,
`rs`.`masterid` AS `unique_id`
FROM  `retailersales` `rs`
LEFT JOIN `retailersalesitem` `r` ON (`r`.`masterid` = `rs`.`masterid`)
LEFT JOIN `franchisemaster` `f` ON((`f`.`Franchisecode` = `r`.`franchisecode`))
LEFT JOIN `branch` `b` ON((`b`.`branchcode` = `f`.`Branch`))
LEFT JOIN `region` `re` ON((`re`.`RegionCode` = `f`.`Region`))
LEFT JOIN `productmaster` `p` ON((`p`.`ProductCode` = `r`.`productcode`))
LEFT JOIN `producttypemaster` `ptm` ON((`ptm`.`ProductTypeCode` = `p`.`ProductType`))
LEFT JOIN `productsegmentmaster` `psm` ON((`psm`.`ProductSegmentCode` = `ptm`.`ProductSegment`))
LEFT JOIN `productgroupmaster` `pgm` ON((`pgm`.`ProductCode` = `psm`.`ProductGroup`))
WHERE ((`rs`.voucherstatus=''ACTIVE'' ))
GROUP BY `r`.`productcode`,`f`.`Franchisename`,`re`.`RegionName`,`b`.`branchname`,`rs`.`VoucherType`,`rs`.`masterid`,`rs`.`salesdate`

UNION ALL

SELECT
`re`.`RegionName` AS `regionname`,
`b`.`branchname` AS `branchname`,
`rs`.`franchisecode` AS `franchisecode`,
`f`.`Franchisename` AS `franchisename`,
`rs`.`replacevocherno` AS `salesno`,
`rs`.`replacedate` AS `salesdates`,
`rs`.`CustomerName` AS `retailername`,
`rs`.`Newproductcode` AS `productcode`,
`p`.`ProductDescription` AS `productdes`,
`pgm`.`ProductGroup` AS `pgroupname`,
`psm`.`ProductSegment` AS `psegmentname`,
`ptm`.`ProductTypeName` AS `ptypename`,
`rs`.`replacetype` AS `VoucherType`,
`rs`.`billqty` AS `quantity`,
(`rs`.`ProrataAmt`-`rs`.`discount`) AS `amount`,
`rs`.`Taxamount` AS `TaxAmount`,
((`rs`.`ProrataAmt` + `rs`.`Taxamount`) - `rs`.`discount`) AS `grossamt`,
`rs`.`dcstatus` AS `voucherstatus`,
`rs`.`pricelevel` AS `preicelevel`,
`rs`.`masterid` AS `unique_id`
FROM `pwview` `rs`
LEFT JOIN `franchisemaster` `f` ON((`f`.`Franchisecode` = `rs`.`franchisecode`))
LEFT JOIN `branch` `b` ON((`b`.`branchcode` = `f`.`Branch`))
LEFT JOIN `region` `re` ON((`re`.`RegionCode` = `f`.`Region`))
LEFT JOIN `productmaster` `p` ON((`p`.`ProductCode` = `rs`.`Newproductcode`))
LEFT JOIN `producttypemaster` `ptm` ON((`ptm`.`ProductTypeCode` = `p`.`ProductType`))
LEFT JOIN `productsegmentmaster` `psm` ON((`psm`.`ProductSegmentCode` = `ptm`.`ProductSegment`))
LEFT JOIN `productgroupmaster` `pgm` ON((`pgm`.`ProductCode` = `psm`.`ProductGroup`))     
WHERE ((`rs`.dcstatus=''ACTIVE''))
GROUP BY `rs`.`masterid`,`rs`.`replacedate`,`rs`.`replacevocherno`,`rs`.`Newproductcode`,`f`.`Franchisename`,`re`.`RegionName`,`b`.`branchname`,`rs`.`replacetype`)rcdrep WHERE 1 ORDER BY `salesdates` ASC';
SET @qry = sqry;
PREPARE stmt FROM @qry;
EXECUTE stmt;
DROP PREPARE stmt;          
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `servicecall`(IN fmdate DATE,IN todate DATE,IN fvalues Varchar(1000000000))
BEGIN
DECLARE sqry VARCHAR(1000000000);
 SET sqry ='SELECT * FROM(
select
`re`.`RegionName` AS `regionname`,
`b`.`branchname` AS `branchname`,
`sc`.`FranchiseeName` AS `franchisecode`,
`f`.`Franchisename` AS `franchiseename`,
`sc`.`CustomerName` AS `retailername`,
`sc`.`SFNo_BSR` AS `SCFNo`,
`sc`.`SFBSRDate` AS `SCFDate`,
`sc`.`complaintdate` AS `complaintdate`,
ts.Category AS category,
ts.DateofSale AS dateofsale,
`sc`.`CustomerProductSlNo` AS `battery_slno`,
`sc`.`ProductCode` AS `productcode`,
`ptm`.`ProductTypeName` AS `ptypename`,
`psm`.`ProductSegment` AS `psegmentname`,
`pgm`.`ProductGroup` AS `pgroupname`,
`sc`.`manufacturedate` AS `manufacturedate`,
ts.CustomerName AS customername,
ts.City AS city,
ts.CustomerPhoneNo AS phoneno,
`sc`.`VehicleInverterModel` AS `vmodel`,
vm.Makename AS vmake,
vs.segmentname AS vsegment,
`sc`.`VehicleRegno` AS `vregno`,
ts.Enginetype AS enginetype,
ifnull((to_days(`sc`.`complaintdate`) - to_days(ts.DateofSale )),0) AS lifeserved,
`sc`.`KMRun` AS `kmrun`,
`sc`.`FailureMode` AS `failuremode`,
`sc`.`Decision` AS `decision`,
`sc`.`DECISIONDATE` AS `decisiondate`,
`sc`.`ClosureDate` AS `closuredate`,
ifnull((to_days(`sc`.`DECISIONDATE`) - to_days(`sc`.`complaintdate`)),0) AS `tat2`,
ifnull((to_days(`sc`.`ClosureDate`) - to_days(`sc`.`complaintdate`)),0) AS `setteled`,
`sc`.`scf_newbslno` AS `newbatterysno`,
`np`.`productcode` AS `newproductcode`,
`nptm`.`ProductTypeName` AS `newptypename`,
`npsm`.`ProductSegment` AS `newpsegmentname`,
`npgm`.`ProductGroup` AS `newpgroupname`,
ifnull((to_days(ts.DateofSale ) - to_days(`sc`.`manufacturedate`)),0) AS leadtime,
sm.CompensationValue AS scompvalue
FROM dcwarranty sc
LEFT JOIN `franchisemaster` `f` ON ((`f`.`Franchisecode` = `sc`.`FranchiseeName`))
LEFT JOIN `branch` `b` ON ((`b`.`branchcode` = `f`.`Branch`))
LEFT JOIN `region` `re` ON ((`re`.`RegionCode` = `f`.`Region`))
LEFT JOIN `productmaster` `p` ON ((`p`.`ProductCode` = `sc`.`ProductCode`))
LEFT JOIN `producttypemaster` `ptm` ON ((`ptm`.`ProductTypeCode` = `p`.`ProductType`))
LEFT JOIN `productsegmentmaster` `psm` ON ((`psm`.`ProductSegmentCode` = `ptm`.`ProductSegment`))
LEFT JOIN `productgroupmaster` `pgm` ON ((`pgm`.`ProductCode` = `psm`.`ProductGroup`))
LEFT JOIN `serialnumbermaster` `ts` ON ((`ts`.`BatterySlNo` = `sc`.`CustomerProductSlNo`))
LEFT JOIN `productmaster` `np` ON ((`np`.`ProductDescription` = `sc`.`scf_newpcode`))
LEFT JOIN `producttypemaster` `nptm` ON ((`nptm`.`ProductTypeCode` = `np`.`ProductType`))
LEFT JOIN `productsegmentmaster` `npsm` ON ((`npsm`.`ProductSegmentCode` = `nptm`.`ProductSegment`))
LEFT JOIN `productgroupmaster` `npgm` ON ((`npgm`.`ProductCode` = `npsm`.`ProductGroup`))
LEFT JOIN `servicemaster` `sm` ON ((`sm`.`Productcode` = `sc`.`ProductCode` AND sm.EffectiveDate <= ts.DateofSale))
LEFT JOIN `vehiclemodel` `vmo` ON ((`vmo`.`modelname` = `sc`.`VehicleInverterModel`))
LEFT JOIN `vehiclemakemaster` `vm` ON ((`vm`.`MakeNo` = `vmo`.`MakeName`))
LEFT JOIN `vehiclesegmentmaster` `vs` ON ((`vs`.`segmentcode` = `vmo`.`segmentname`))
WHERE (`sc`.voucherstatus = ''ACTIVE'' AND `sc`.`SFBSRDate` >= ''fmdate'' AND `sc`.`SFBSRDate` <= ''todate'')
GROUP BY `sc`.`SFNo_BSR`,`sc`.`FranchiseeName`
 )rcdrep WHERE QUERYCON ORDER BY SCFDate ASC';
SET sqry = REPLACE(sqry,'QUERYCON',fvalues);
SET sqry = REPLACE(sqry,'fmdate',fmdate);
SET @qry = REPLACE(sqry,'todate',todate);     
PREPARE stmt FROM @qry;
EXECUTE stmt;
DROP PREPARE stmt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `stockday`()
BEGIN
TRUNCATE stock_day;	
INSERT INTO stock_day (franchiseecode,productcode,stockdate,receipt,issue) 
SELECT * FROM(SELECT franchisecode,productcode,vchdate,SUM(purqty - pur_rqty) AS inwards,SUM(saleqty - sals_rqty) AS outwards 
FROM ( SELECT r.salesdates AS vchdate,
       r.productcode AS productcode,
       sum(r.quantity) AS saleqty,
       0 AS purqty,
	   0 AS sals_rqty,
	   0 AS pur_rqty,
       r.franchisecode AS franchisecode
FROM r_salesreport r 
GROUP BY r.salesdates,r.productcode,r.franchisecode
UNION ALL
SELECT p.purchasedate AS vchdate,
       p.productcode AS productcode,
       0 AS saleqty,
       sum(p.quantity) AS purqty,
	   0 AS sals_rqty,
	   0 AS pur_rqty,
       p.franchisecode AS franchisecode
FROM r_purchasereport p
GROUP BY p.purchasedate,p.productcode,p.franchisecode
UNION ALL
SELECT sr.salesRetdates AS vchdate,
       sr.productcode AS productcode,
	   0 AS saleqty,
       0 AS purqty,
	   sum(sr.quantity) AS sals_rqty,
	   0 AS pur_rqty,
       sr.franchisecode AS franchisecode
FROM r_salesreturn sr
GROUP BY sr.salesRetdates,sr.productcode,sr.franchisecode
UNION ALL
SELECT pr.purchaseRetdate AS vchdate,
       pr.productcode AS productcode,
	   0 AS saleqty,
       0 AS purqty,
	   0 AS sals_rqty,
	   sum(pr.quantity) AS pur_rqty,
       pr.franchisecode AS franchisecode
FROM r_purchasereturn pr
GROUP BY pr.purchaseRetdate,pr.productcode,pr.franchisecode
UNION ALL
SELECT slr.stockdate AS vchdate,
       slr.productcode AS productcode,
	   CASE WHEN (slr.openstock<0) THEN ABS(slr.openstock) ELSE
	   0 END AS saleqty,
	   CASE WHEN (slr.openstock>0) THEN ABS(slr.openstock) ELSE
	   0 END AS purqty,
	   0 AS sals_rqty,
	   0 AS pur_rqty,
       slr.franchiseecode AS franchisecode
FROM stock_open slr
GROUP BY slr.stockdate,slr.productcode,slr.franchiseecode) inoutwards 
WHERE  NOT(productcode='' OR franchisecode='')
GROUP BY productcode,franchisecode,vchdate)stockday  where NOT(inwards='0' AND outwards='0');
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `stockmonth`()
BEGIN

DECLARE monthyear DATE;
TRUNCATE stock_month;
SET monthyear = DATE_FORMAT(CURDATE(),'%Y-%m-01');
SET @month=period_diff(DATE_FORMAT(monthyear,'%Y%m'),DATE_FORMAT('2013-04-01','%Y%m'));
 WHILE @month>=1 DO
 INSERT INTO stock_month (franchiseecode,productcode,stockdate,openstock)
      SELECT franchiseecode,productcode,DATE_FORMAT(monthyear,'%Y-%m') AS stockdates,
			SUM(receipt-issue) AS openingstock
FROM stock_day where stockdate<monthyear
			GROUP BY franchiseecode,productcode;
SET monthyear= DATE_SUB(monthyear, INTERVAL 1 MONTH);
    SET @month= @month-1;
	END WHILE;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `stockopen`()
BEGIN
	
INSERT INTO stock_open(franchiseecode,productcode,stockdate,openstock)
SELECT sr.franchiseecode,sr.productcode,sr.opdate,
CASE 
	WHEN IFNULL(sr.openstock,0) = IFNULL(cs.StockonHand,0) THEN 
		0
	ELSE
    IFNULL(sr.openstock,0)-IFNULL(cs.StockonHand,0)
	END AS openstock
FROM stockledgerreport sr
LEFT JOIN view_closingstock cs ON sr.franchiseecode=cs.franchiseecode AND sr.productcode=cs.productcode AND sr.opdate=cs.opdate
where sr.mdate=CURDATE()
GROUP BY sr.franchiseecode,sr.productcode,sr.opdate;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `stockreport`(IN fmdate DATE,IN todate DATE,IN fvalues Varchar(10000))
BEGIN
DECLARE monthy DATE;
DECLARE monthyear VARCHAR(15);


SET monthyear =  DATE_FORMAT(fmdate,'%Y-%m');
SET @qry = CONCAT('SELECT
vr.RegionName,
vr.branchname,
stockoutput.franchiseecode AS franchisecode,
vr.Franchisename,
pt.ProductGroup AS pgroupname,
pt.psegmentname,
pt.ProductTypeName AS ptypename,
stockoutput.pc AS productcode,
pt.ProductDescription AS productdes,
SUM(stockoutput.openstock) AS opstock, 
SUM(stockoutput.receipt) AS ReceivedQty,
SUM(stockoutput.issue) AS IsseudQty, 
SUM(stockoutput.openstock+stockoutput.receipt) - SUM(stockoutput.issue) AS StockonHand,
DATE_FORMAT(NOW(),''%d-%m-%Y'') AS rundate
FROM(
SELECT franchiseecode,
       productcode as pc,
       0 AS openstock,
       receipt,
       issue
FROM stock_day
WHERE stockdate BETWEEN ''',fmdate,''' AND ''',todate,'''

UNION ALL

SELECT franchiseecode,
       productcode as pc,
       openstock,
       0 AS receipt,
       0 AS issue
FROM stock_month
WHERE (stockdate =''',monthyear,'''))stockoutput
LEFT JOIN view_rbrs vr ON vr.Franchisecode = stockoutput.franchiseecode
LEFT JOIN view_rptproductfin pt ON pt.ProductCode = stockoutput.pc
WHERE ',fvalues,'
group by stockoutput.franchiseecode,stockoutput.pc');
PREPARE stmt FROM @qry;
EXECUTE stmt;
DROP PREPARE stmt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `WSreport`(IN fmdate DATE,IN todate DATE,IN fvalues Varchar(1000000000))
BEGIN
DECLARE sqry VARCHAR(1000000000);
 SET sqry ='SELECT * FROM(
 SELECT
re.RegionName AS regionname,
b.branchname AS branchname,
sc.FranchiseeName AS franchisecode,
f.Franchisename AS franchiseename,
sc.SFNo_BSR AS SCFNo,
sc.SFBSRDate AS SCFDate,
sc.ProductCode AS productcode,
ptm.ProductTypeName AS ptypename,
psm.ProductSegment AS psegmentname,
pgm.ProductGroup AS pgroupname,
sc.CustomerProductSlNo AS battery_slno,
sc.FailureMode AS failuremode,
sc.Decision AS decision,
pm.ProductCode AS newproductcode,
sc.scf_newbslno AS newbatterysno,


CASE
WHEN (sc.dcstatus=''ACTIVE'') THEN
sc.replacevocherno
ELSE
''''
END as DCNo,

CASE
WHEN (sc.dcstatus=''ACTIVE'') THEN
sc.replacedate
ELSE
''''
END AS DCDate,


wr.DCWNo AS DCWRNo,
wr.DCWDate AS DCWRDate,
CASE
WHEN ((CONVERT (wmreceipt.qty,SIGNED INTEGER) >= CONVERT(wr.RowNumber,SIGNED INTEGER))) THEN wmreceipt.grnno
ELSE
CASE WHEN ((CONVERT (prow.tqty,SIGNED INTEGER) >= CONVERT(wr.RowNumber,SIGNED INTEGER))) THEN pmreceipt.grnno ELSE
'''' END
END  AS GRNno,
CASE
WHEN ((CONVERT (wmreceipt.qty,SIGNED INTEGER) >= CONVERT(wr.RowNumber,SIGNED INTEGER))) THEN wmreceipt.grndate
ELSE
CASE WHEN ((CONVERT (prow.tqty,SIGNED INTEGER) >= CONVERT(wr.RowNumber,SIGNED INTEGER))) THEN pmreceipt.grndate ELSE
'''' END
END  AS GRNDate

FROM dcwarranty sc
LEFT JOIN
(SELECT  @row_num := IF(@prev_DCWNo=dd.DCWNo AND @prev_ProductCode=dd.ProductCode,@row_num+1,1) AS RowNumber, dd.*,
       @prev_DCWNo := dd.DCWNo,
       @prev_ProductCode := dd.ProductCode
  FROM dcwarrantyreturn_details dd,
      (SELECT @row_num := 1) x,
      (SELECT @prev_DCWNo := '''') y,
      (SELECT @prev_ProductCode := '''') z
  WHERE dd.voucherstatus = ''ACTIVE''
  ORDER BY dd.DCWNo,dd.ProductCode DESC ) wr ON ((wr.scfno = sc.SFNo_BSR))
LEFT JOIN franchisemaster f ON ((f.Franchisecode = sc.FranchiseeName))
LEFT JOIN branch b ON ((b.branchcode = f.Branch))
LEFT JOIN region re ON ((re.RegionCode = f.Region))
LEFT JOIN productmaster p ON ((p.ProductCode = sc.ProductCode))
LEFT JOIN producttypemaster ptm ON ((ptm.ProductTypeCode = p.ProductType))
LEFT JOIN productsegmentmaster psm ON ((psm.ProductSegmentCode = ptm.ProductSegment))
LEFT JOIN productgroupmaster pgm ON ((pgm.ProductCode = psm.ProductGroup))
LEFT JOIN productmaster pm ON ((pm.ProductDescription = sc.scf_newpcode))
LEFT JOIN 
(SELECT
	wmr.wmno AS grnno,
	wmr.wmdatedate AS grndate,
	wrd.ProductCode AS ProductCode,
	wrd.Quantity AS qty,
  wmr.voucherstatus as voucherstatus,
	wmr.dcwno AS dcwno
FROM wmaterialreceipt wmr
LEFT JOIN wmaterial_details wrd
	ON wrd.masterid = wmr.masterid) wmreceipt
	ON wmreceipt.dcwno = wr.DCWNo AND sc.ProductCode = wmreceipt.ProductCode AND wmreceipt.voucherstatus = ''ACTIVE''

LEFT JOIN
(SELECT
	pmr.pmno AS grnno,
	pmr.pmdatedate AS grndate,
	prd.ProductCode AS ProductCode,
	prd.Quantity AS qty,
  pmr.voucherstatus as voucherstatus,
	pmr.dcwno AS dcwno
FROM proratamaterial pmr
LEFT JOIN proratamaterial_details prd
ON prd.masterid = pmr.masterid) pmreceipt
ON pmreceipt.dcwno = wr.DCWNo AND sc.ProductCode = pmreceipt.ProductCode AND pmreceipt.voucherstatus = ''ACTIVE''

LEFT JOIN (
SELECT prw.ProductCode,prw.dcwno,SUM(prw.qty) AS tqty FROM(
SELECT
	wrd.ProductCode AS ProductCode,
	wrd.Quantity AS qty,
	wm.dcwno AS dcwno
FROM wmaterialreceipt wm
LEFT JOIN wmaterial_details wrd
	ON wrd.masterid = wm.masterid AND wrd.voucherstatus = ''ACTIVE''
UNION
SELECT
	prd.ProductCode AS ProductCode,
	prd.Quantity AS qty,
	pm.dcwno AS dcwno
FROM proratamaterial pm
LEFT JOIN proratamaterial_details prd
ON prd.masterid = pm.masterid AND prd.voucherstatus = ''ACTIVE''
) prw
GROUP BY prw.ProductCode,prw.dcwno) prow ON prow.dcwno = wr.DCWNo AND sc.ProductCode = prow.ProductCode
WHERE (sc.voucherstatus = ''ACTIVE'' AND sc.SFBSRDate >= ''fmdate'' AND sc.SFBSRDate <= ''todate'')
GROUP BY sc.SFNo_BSR,sc.FranchiseeName
) wandsrep WHERE QUERYCON ORDER BY franchisecode,DCWRNo ASC';

SET sqry = REPLACE(sqry,'QUERYCON',fvalues);
SET sqry = REPLACE(sqry,'fmdate',fmdate);
SET @qry = REPLACE(sqry,'todate',todate);
PREPARE stmt FROM @qry;
EXECUTE stmt;
DROP PREPARE stmt;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `percalc`(slno VARCHAR(255)) RETURNS char(160) CHARSET latin1
BEGIN
      DECLARE bstatus CHAR(160) ;
      DECLARE sl CHAR(100);

    REPEAT
      select oldbatteryno,batterystatus INTO @oldsl,@bstatus from serialnumbermaster where (BatterySlNo = slno);
      IF @bstatus = 'REPLACE' THEN
        SET slno = @oldsl;
      END IF;
    UNTIL @bstatus = 'NEW' END REPEAT;

    RETURN slno;
  END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `SPLIT_STR`(
  x VARCHAR(255),
  delim VARCHAR(12),
  pos INT
) RETURNS varchar(255) CHARSET latin1
RETURN case when CHAR_LENGTH(SUBSTRING_INDEX(x, delim, pos -1)) = CHAR_LENGTH(x) then x else (REPLACE(SUBSTRING(SUBSTRING_INDEX(x, delim, pos),
       CHAR_LENGTH(SUBSTRING_INDEX(x, delim, pos -1)) + 1),
       delim, '')) end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

CREATE TABLE IF NOT EXISTS `branch` (
  `branchcode` varchar(15) NOT NULL DEFAULT '',
  `branchname` varchar(50) DEFAULT NULL,
  `region` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `m_date` datetime DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`branchcode`),
  KEY `Id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=61 ;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`branchcode`, `branchname`, `region`, `country`, `user_id`, `m_date`, `id`) VALUES
('1001', 'Hyderabad', 'Central', 'IN', '1000213', '2013-05-17 12:40:02', 38),
('1002', 'Vijayawada', 'Central', 'IN', 'admin', '2013-06-04 15:38:56', 39),
('1003', 'Guwahati', 'East1', 'IN', '', '0000-00-00 00:00:00', 40),
('1004', 'Patna', 'East2', 'IN', '1000213', '2013-07-05 17:47:13', 41),
('1005', 'Ahmedabad', 'West2', 'IN', '', '0000-00-00 00:00:00', 42),
('1006', 'Bangalore', 'South1', 'IN', '', '0000-00-00 00:00:00', 43),
('1007', 'Cochin', 'South1', 'IN', '1000888', '2013-06-11 17:21:09', 44),
('1008', 'Indore', 'West2', 'IN', '', '0000-00-00 00:00:00', 45),
('1009', 'Mumbai', 'West1', 'IN', '', '0000-00-00 00:00:00', 46),
('1010', 'Pune', 'West1', 'IN', '1000888', '2013-06-07 11:02:34', 47),
('1011', 'Nagpur', 'Central', 'IN', '', '0000-00-00 00:00:00', 48),
('1012', 'Bhubaneswar', 'East1', 'IN', '', '0000-00-00 00:00:00', 49),
('1013', 'Chandigarh', 'North2', 'IN', '', '0000-00-00 00:00:00', 50),
('1014', 'Jaipur', 'North2', 'IN', '', '0000-00-00 00:00:00', 51),
('1015', 'Chennai', 'South2', 'IN', '', '0000-00-00 00:00:00', 52),
('1016', 'Coimbatore', 'South2', 'IN', '', '0000-00-00 00:00:00', 53),
('1017', 'Ghaziabad', 'North1', 'IN', '', '0000-00-00 00:00:00', 54),
('1018', 'Lucknow', 'North1', 'IN', '1000888', '2013-05-29 12:44:43', 55),
('1019', 'Kolkatta', 'East1', 'IN', '', '0000-00-00 00:00:00', 56),
('1020', 'Delhi', 'North1', 'IN', '', '0000-00-00 00:00:00', 57),
('1021', 'Jamshedpur', 'East2', 'IN', '1000888', '2013-07-06 13:32:27', 58),
('1023', 'Gurgaon', 'North2', 'IN', '', '0000-00-00 00:00:00', 60);

-- --------------------------------------------------------

--
-- Table structure for table `countrymaster`
--

CREATE TABLE IF NOT EXISTS `countrymaster` (
  `countrycode` varchar(15) NOT NULL DEFAULT '',
  `countryname` varchar(50) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `m_date` datetime DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`countrycode`),
  KEY `Id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `countrymaster`
--

INSERT INTO `countrymaster` (`countrycode`, `countryname`, `user_id`, `m_date`, `id`) VALUES
('IN', 'INDIA', 'admin', '2014-09-17 16:54:09', 1);

-- --------------------------------------------------------

--
-- Table structure for table `daylogic`
--

CREATE TABLE IF NOT EXISTS `daylogic` (
  `daycode` varchar(15) NOT NULL DEFAULT '',
  `dayvalue` int(15) DEFAULT NULL,
  `id` int(15) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`daycode`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

--
-- Dumping data for table `daylogic`
--

INSERT INTO `daylogic` (`daycode`, `dayvalue`, `id`) VALUES
('1', 1, 1),
('2', 2, 2),
('3', 3, 3),
('4', 4, 4),
('5', 5, 5),
('6', 6, 6),
('7', 7, 7),
('8', 8, 8),
('9', 9, 9),
('A', 10, 10),
('B', 11, 11),
('C', 12, 12),
('D', 13, 13),
('E', 14, 14),
('F', 15, 15),
('G', 16, 16),
('H', 17, 17),
('I', 18, 18),
('J', 19, 19),
('K', 20, 20),
('L', 21, 21),
('M', 22, 22),
('N', 23, 23),
('O', 24, 24),
('P', 25, 25),
('Q', 26, 26),
('R', 27, 27),
('S', 28, 28),
('T', 29, 29),
('U', 30, 30),
('V', 31, 31);

-- --------------------------------------------------------

--
-- Table structure for table `dcwarranty`
--

CREATE TABLE IF NOT EXISTS `dcwarranty` (
  `SFNo_BSR` varchar(50) NOT NULL,
  `SFBSRDate` date NOT NULL,
  `CustomerProductSlNo` varchar(50) NOT NULL,
  `ProductCode` varchar(100) NOT NULL,
  `CustomerComplaint` varchar(75) NOT NULL,
  `CustomerName` varchar(75) NOT NULL,
  `VehicleSegment` varchar(75) NOT NULL,
  `VehicleRegno` varchar(30) NOT NULL,
  `VehicleInverermake` varchar(75) NOT NULL,
  `VehicleInverterModel` varchar(75) NOT NULL,
  `Salestype` varchar(30) NOT NULL,
  `KMRun` varchar(30) NOT NULL,
  `ServeredLife` varchar(30) NOT NULL,
  `RetailerName` varchar(75) DEFAULT NULL,
  `FranchiseeName` varchar(75) NOT NULL,
  `FailureMode` varchar(75) NOT NULL,
  `Remarks` varchar(50) NOT NULL,
  `Decision` varchar(50) NOT NULL,
  `NewproductSlNo` varchar(50) NOT NULL,
  `Newproductcode` varchar(50) NOT NULL,
  `productcodedescription` varchar(100) NOT NULL,
  `billqty` int(11) unsigned NOT NULL DEFAULT '0',
  `rate` double NOT NULL DEFAULT '0',
  `ClosureDate` date NOT NULL,
  `replacedate` date NOT NULL,
  `replacevocherno` varchar(50) NOT NULL,
  `replacetype` varchar(50) NOT NULL,
  `ProrataAmt` double NOT NULL,
  `discount` double NOT NULL DEFAULT '0',
  `pricelevel` varchar(100) NOT NULL,
  `ledgername` varchar(100) NOT NULL,
  `voucherstatus` varchar(30) NOT NULL,
  `dcstatus` varchar(20) NOT NULL,
  `DECISIONDATE` varchar(30) NOT NULL,
  `complaintdate` date NOT NULL,
  `manufacturedate` date NOT NULL,
  `tempserilano` varchar(50) NOT NULL,
  `invoicedate` date NOT NULL,
  `feedback1` varchar(45) NOT NULL,
  `feedback2` varchar(45) NOT NULL,
  `feedback3` varchar(45) NOT NULL,
  `feedback4` varchar(45) NOT NULL,
  `feedback5` varchar(45) NOT NULL,
  `masterid` varchar(45) NOT NULL,
  `repmasterid` varchar(45) NOT NULL,
  `taxvalue` double NOT NULL DEFAULT '0',
  `scf_newbslno` varchar(50) NOT NULL,
  `scf_newpcode` varchar(50) NOT NULL,
  `orginalpc` varchar(50) NOT NULL,
  `wstatus` int(11) unsigned NOT NULL,
  PRIMARY KEY (`masterid`) USING BTREE,
  UNIQUE KEY `masterid` (`masterid`),
  KEY `new_index` (`SFBSRDate`,`replacedate`,`replacetype`,`voucherstatus`,`dcstatus`,`repmasterid`),
  KEY `index_3` (`SFBSRDate`,`ProductCode`,`Salestype`,`voucherstatus`),
  KEY `index_4` (`Newproductcode`,`replacedate`,`replacevocherno`,`replacetype`,`dcstatus`,`repmasterid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Triggers `dcwarranty`
--
DROP TRIGGER IF EXISTS `after_wsales_update`;
DELIMITER //
CREATE TRIGGER `after_wsales_update` AFTER UPDATE ON `dcwarranty`
 FOR EACH ROW BEGIN
		DECLARE Region_Name VARCHAR(50);
		DECLARE branch_name VARCHAR(50);
		DECLARE Franchise_name VARCHAR(50);
		DECLARE p_description VARCHAR(100);
		DECLARE p_typename VARCHAR(50);
		DECLARE p_segmentname VARCHAR(50);
		DECLARE p_groupname VARCHAR(50);
		DECLARE amt INT(10);
		DECLARE gross_amt INT(10);
		DECLARE Tax_amount INT(10);
		DECLARE discount_amt INT(10);

		SELECT branchname,RegionName,Franchisename 
		INTO @branch_name,@Region_Name,@Franchise_name 
		FROM view_rbrs
		WHERE view_rbrs.Franchisecode = NEW.FranchiseeName;

		SELECT pdescription,ptypename,psegmentname,pgroupname
		INTO @p_description,@p_typename,@p_segmentname,@p_groupname 
		FROM view_productdtetails
		WHERE view_productdtetails.pcode = NEW.Newproductcode;
			
		
		IF NEW.dcstatus = 'ACTIVE' THEN
			IF NEW.replacetype = 'warranty sales' THEN
				SELECT COUNT(repmasterid) INTO @discount_amt
				FROM dcwarranty WHERE repmasterid=NEW.repmasterid;
				IF @discount_amt > 0 THEN
				  DELETE FROM r_salesreport WHERE unique_id = NEW.repmasterid;
				END IF;
				INSERT INTO r_salesreport
				(regionname,branchname,franchisecode,franchisename,salesno,salesdates,retailername,productcode,productdes,pgroupname,psegmentname,ptypename,VoucherType,quantity,amount,TaxAmount,grossamt,voucherstatus,preicelevel,unique_id)
				VALUES
				(@Region_Name,@branch_name,NEW.FranchiseeName,@Franchise_name,NEW.replacevocherno,NEW.replacedate,OLD.CustomerName,NEW.Newproductcode,@p_description,@p_groupname,@p_segmentname,@p_typename,NEW.replacetype,NEW.billqty,0,0,0,NEW.dcstatus,NEW.pricelevel,NEW.repmasterid);

			ELSEIF NEW.replacetype = 'Prorata sales' THEN
				SELECT ROUND(SUM((CASE WHEN (`Taxledger` NOT IN ('Discount','Prorata Charges')) THEN `Taxamount` ELSE 0 END)),0),ROUND(SUM((CASE WHEN (`Taxledger` IN ('Discount','Prorata Charges')) THEN `Taxamount` ELSE 0 END)),0)
				INTO @Tax_amount,@discount_amt
				FROM `dcwarrantyledger` WHERE masterid=NEW.repmasterid
				GROUP BY `masterid`,`replacedate`,`franchisecode`;
				SET @gross_amt = NEW.ProrataAmt + @Tax_amount - @discount_amt;
				SET @amt = NEW.ProrataAmt - @discount_amt;
				INSERT INTO r_salesreport 
				(regionname,branchname,franchisecode,franchisename,salesno,salesdates,retailername,productcode,productdes,pgroupname,psegmentname,ptypename,VoucherType,quantity,amount,TaxAmount,grossamt,voucherstatus,preicelevel,unique_id)
				VALUES
				(@Region_Name,@branch_name,NEW.FranchiseeName,@Franchise_name,NEW.replacevocherno,NEW.replacedate,OLD.CustomerName,NEW.Newproductcode,@p_description,@p_groupname,@p_segmentname,@p_typename,NEW.replacetype,NEW.billqty,@amt,@Tax_amount,@gross_amt,NEW.dcstatus,NEW.pricelevel,NEW.repmasterid);
			END IF;
			
		ELSEIF NEW.dcstatus = 'CANCEL' THEN
			DELETE FROM r_salesreport WHERE unique_id = NEW.repmasterid;
		END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `dcwarrantyledger`
--

CREATE TABLE IF NOT EXISTS `dcwarrantyledger` (
  `replacevocherno` varchar(50) NOT NULL,
  `replacedate` date NOT NULL,
  `Taxledger` varchar(50) NOT NULL,
  `Taxamount` double NOT NULL,
  `franchisecode` varchar(20) NOT NULL,
  `voucherstatus` varchar(30) NOT NULL,
  `masterid` varchar(50) NOT NULL,
  `percentage` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Triggers `dcwarrantyledger`
--
DROP TRIGGER IF EXISTS `after_wsales_delete`;
DELIMITER //
CREATE TRIGGER `after_wsales_delete` AFTER DELETE ON `dcwarrantyledger`
 FOR EACH ROW BEGIN
		DELETE FROM r_salesreport WHERE unique_id = OLD.masterid;	
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `dcwarrantyreturn`
--

CREATE TABLE IF NOT EXISTS `dcwarrantyreturn` (
  `DCWarrantyReturnNo` varchar(50) NOT NULL DEFAULT '',
  `DCWarrantyreturnDate` date DEFAULT NULL,
  `SalesType` varchar(50) DEFAULT NULL,
  `partyledger` varchar(30) DEFAULT NULL,
  `Decision` varchar(50) DEFAULT NULL,
  `VoucherType` varchar(30) DEFAULT NULL,
  `franchisecode` varchar(50) DEFAULT NULL,
  `voucherstatus` varchar(30) DEFAULT NULL,
  `masterid` varchar(45) NOT NULL,
  PRIMARY KEY (`masterid`),
  UNIQUE KEY `masterid` (`masterid`),
  KEY `DCWarrantyReturnNo` (`DCWarrantyReturnNo`),
  KEY `index_4` (`DCWarrantyreturnDate`,`VoucherType`,`franchisecode`,`voucherstatus`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dcwarrantyreturnledger`
--

CREATE TABLE IF NOT EXISTS `dcwarrantyreturnledger` (
  `DCWNO` varchar(50) DEFAULT NULL,
  `DCWdate` date DEFAULT NULL,
  `Taxledger` varchar(50) DEFAULT NULL,
  `Taxamount` double DEFAULT NULL,
  `franchisecode` varchar(20) DEFAULT NULL,
  `voucherstatus` varchar(30) DEFAULT NULL,
  `masterid` varchar(45) NOT NULL,
  KEY `index_1` (`masterid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `dcwarrantyreturn_details`
--

CREATE TABLE IF NOT EXISTS `dcwarrantyreturn_details` (
  `ProductCode` varchar(50) DEFAULT NULL,
  `ProductDescription` varchar(50) DEFAULT NULL,
  `Quantity` int(30) DEFAULT NULL,
  `Rate` double DEFAULT NULL,
  `Amount` float DEFAULT NULL,
  `DCWNo` varchar(50) DEFAULT NULL,
  `DCWDate` date DEFAULT NULL,
  `FranchiseCode` varchar(50) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `voucherstatus` varchar(30) DEFAULT NULL,
  `scfno` varchar(30) DEFAULT NULL,
  `SCFDATE` date DEFAULT NULL,
  `masterid` varchar(45) NOT NULL,
  KEY `index_1` (`ProductCode`,`voucherstatus`,`masterid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `downloadstatus`
--

CREATE TABLE IF NOT EXISTS `downloadstatus` (
  `franchisecode` int(30) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `master` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  KEY `index_1` (`franchisecode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `downloadstatus`
--

INSERT INTO `downloadstatus` (`franchisecode`, `date`, `master`, `status`) VALUES
(6351019, '2015-02-10', 'PSegment', 'Delivered'),
(6351019, '2015-02-10', 'PType', 'Delivered'),
(6351019, '2015-02-10', 'PUOM', 'Delivered'),
(6351019, '2015-02-10', 'Product', 'Delivered'),
(6351019, '2015-02-10', 'PWarranty', 'Delivered'),
(6351019, '2015-02-10', 'Product Warranty Is Created Successfully', 'Delivered'),
(6351019, '2015-02-10', 'PMapping', 'Delivered'),
(6351019, '2015-02-10', 'PService', 'Delivered'),
(6351019, '2015-02-10', 'RCategory', 'Delivered'),
(6351019, '2015-02-10', 'VMake', 'Delivered'),
(6351019, '2015-02-10', 'VSegment', 'Delivered'),
(6351019, '2015-02-10', 'VModel', 'Delivered'),
(6351019, '2015-02-10', 'FMode', 'Delivered'),
(6351019, '2015-02-10', 'Scheme', 'Delivered'),
(6351019, '2015-02-10', 'OEM', 'Delivered'),
(6351019, '2015-02-10', 'PSegment', 'Delivered'),
(6351019, '2015-02-10', 'PType', 'Delivered'),
(6351019, '2015-02-10', 'PUOM', 'Delivered'),
(6351019, '2015-02-10', 'Product', 'Delivered'),
(6351019, '2015-02-10', 'PWarranty', 'Delivered'),
(6351019, '2015-02-10', 'Product Warranty Is Created Successfully', 'Delivered'),
(6351019, '2015-02-10', 'PMapping', 'Delivered'),
(6351019, '2015-02-10', 'PService', 'Delivered'),
(6351019, '2015-02-10', 'RCategory', 'Delivered'),
(6351019, '2015-02-10', 'VMake', 'Delivered'),
(6351019, '2015-02-10', 'VSegment', 'Delivered'),
(6351019, '2015-02-10', 'VModel', 'Delivered'),
(6351019, '2015-02-10', 'FMode', 'Delivered'),
(6351019, '2015-02-10', 'Scheme', 'Delivered'),
(6351019, '2015-02-10', 'OEM', 'Delivered'),
(6351019, '2015-02-10', 'PSegment', 'Delivered'),
(6351019, '2015-02-10', 'product Segment', 'Delivered'),
(6351019, '2015-02-10', 'PType', 'Delivered'),
(6351019, '2015-02-10', 'product Type', 'Delivered'),
(6351019, '2015-02-10', 'PUOM', 'Delivered'),
(6351019, '2015-02-10', 'product UOM', 'Delivered'),
(6351019, '2015-02-10', 'Product', 'Delivered'),
(6351019, '2015-02-10', 'product Master', 'Delivered'),
(6351019, '2015-02-10', 'PWarranty', 'Delivered'),
(6351019, '2015-02-10', 'Product Warranty Is Created Successfully', 'Delivered'),
(6351019, '2015-02-10', 'PMapping', 'Delivered'),
(6351019, '2015-02-10', 'product Mapping', 'Delivered'),
(6351019, '2015-02-10', 'PService', 'Delivered'),
(6351019, '2015-02-10', 'product Service', 'Delivered'),
(6351019, '2015-02-10', 'PriceList', 'Delivered'),
(6351019, '2015-02-10', 'RCategory', 'Delivered'),
(6351019, '2015-02-10', 'Retailer Category', 'Delivered'),
(6351019, '2015-02-10', 'VMake', 'Delivered'),
(6351019, '2015-02-10', 'Vehicle Make', 'Delivered'),
(6351019, '2015-02-10', 'VSegment', 'Delivered'),
(6351019, '2015-02-10', 'Vehicle Segment', 'Delivered'),
(6351019, '2015-02-10', 'VModel', 'Delivered'),
(6351019, '2015-02-10', 'Vehicle Model', 'Delivered'),
(6351019, '2015-02-10', 'FMode', 'Delivered'),
(6351019, '2015-02-10', 'Failure Mode', 'Delivered'),
(6351019, '2015-02-10', 'Scheme', 'Delivered'),
(6351019, '2015-02-10', 'Scheme master', 'Delivered'),
(6351019, '2015-02-10', 'OEM', 'Delivered'),
(0, '2015-03-04', 'PSegment', 'Delivered'),
(0, '2015-03-04', 'PType', 'Delivered'),
(0, '2015-03-04', 'PUOM', 'Delivered'),
(0, '2015-03-04', 'Product', 'Delivered'),
(0, '2015-03-04', 'PWarranty', 'Delivered'),
(0, '2015-03-04', 'PMapping', 'Delivered'),
(0, '2015-03-04', 'PService', 'Delivered'),
(0, '2015-03-04', 'PriceList', 'Delivered'),
(0, '2015-03-04', 'RCategory', 'Delivered'),
(0, '2015-03-04', 'VMake', 'Delivered'),
(0, '2015-03-04', 'VSegment', 'Delivered'),
(0, '2015-03-04', 'VModel', 'Delivered'),
(0, '2015-03-04', 'FMode', 'Delivered'),
(0, '2015-03-04', 'Scheme', 'Delivered'),
(0, '2015-03-04', 'OEM', 'Delivered'),
(0, '2015-03-04', 'PSegment', 'Delivered'),
(0, '2015-03-04', 'PType', 'Delivered'),
(0, '2015-03-04', 'PUOM', 'Delivered'),
(0, '2015-03-04', 'Product', 'Delivered'),
(0, '2015-03-04', 'PWarranty', 'Delivered'),
(0, '2015-03-04', 'PMapping', 'Delivered'),
(0, '2015-03-04', 'PService', 'Delivered'),
(0, '2015-03-04', 'PriceList', 'Delivered'),
(0, '2015-03-04', 'RCategory', 'Delivered'),
(0, '2015-03-04', 'VMake', 'Delivered'),
(0, '2015-03-04', 'VSegment', 'Delivered'),
(0, '2015-03-04', 'VModel', 'Delivered'),
(0, '2015-03-04', 'FMode', 'Delivered'),
(0, '2015-03-04', 'Scheme', 'Delivered'),
(0, '2015-03-04', 'OEM', 'Delivered'),
(0, '2015-03-04', 'PSegment', 'Delivered'),
(0, '2015-03-04', 'PType', 'Delivered'),
(0, '2015-03-04', 'PUOM', 'Delivered'),
(0, '2015-03-04', 'Product', 'Delivered'),
(0, '2015-03-04', 'PWarranty', 'Delivered'),
(0, '2015-03-04', 'PMapping', 'Delivered'),
(0, '2015-03-04', 'PService', 'Delivered'),
(0, '2015-03-04', 'PriceList', 'Delivered'),
(0, '2015-03-04', 'RCategory', 'Delivered'),
(0, '2015-03-04', 'VMake', 'Delivered'),
(0, '2015-03-04', 'VSegment', 'Delivered'),
(0, '2015-03-04', 'VModel', 'Delivered'),
(0, '2015-03-04', 'FMode', 'Delivered'),
(0, '2015-03-04', 'Scheme', 'Delivered'),
(0, '2015-03-04', 'OEM', 'Delivered'),
(0, '2015-03-04', 'PSegment', 'Delivered'),
(0, '2015-03-04', 'PType', 'Delivered'),
(0, '2015-03-04', 'PUOM', 'Delivered'),
(0, '2015-03-04', 'Product', 'Delivered'),
(0, '2015-03-04', 'PWarranty', 'Delivered'),
(0, '2015-03-04', 'PMapping', 'Delivered'),
(0, '2015-03-04', 'PService', 'Delivered'),
(0, '2015-03-04', 'PriceList', 'Delivered'),
(0, '2015-03-04', 'RCategory', 'Delivered'),
(0, '2015-03-04', 'VMake', 'Delivered'),
(0, '2015-03-04', 'VSegment', 'Delivered'),
(0, '2015-03-04', 'VModel', 'Delivered'),
(0, '2015-03-04', 'FMode', 'Delivered'),
(0, '2015-03-04', 'Scheme', 'Delivered'),
(0, '2015-03-04', 'OEM', 'Delivered'),
(0, '2015-03-04', 'PSegment', 'Delivered'),
(0, '2015-03-04', 'PType', 'Delivered'),
(0, '2015-03-04', 'PUOM', 'Delivered'),
(0, '2015-03-04', 'Product', 'Delivered'),
(0, '2015-03-04', 'PWarranty', 'Delivered'),
(0, '2015-03-04', 'PMapping', 'Delivered'),
(0, '2015-03-04', 'PService', 'Delivered'),
(0, '2015-03-04', 'PriceList', 'Delivered'),
(0, '2015-03-04', 'RCategory', 'Delivered'),
(0, '2015-03-04', 'VMake', 'Delivered'),
(0, '2015-03-04', 'VSegment', 'Delivered'),
(0, '2015-03-04', 'VModel', 'Delivered'),
(0, '2015-03-04', 'FMode', 'Delivered'),
(0, '2015-03-04', 'Scheme', 'Delivered'),
(0, '2015-03-04', 'OEM', 'Delivered'),
(0, '2015-03-04', 'PSegment', 'Delivered'),
(0, '2015-03-04', 'PType', 'Delivered'),
(0, '2015-03-04', 'PUOM', 'Delivered'),
(0, '2015-03-04', 'Product', 'Delivered'),
(0, '2015-03-04', 'PWarranty', 'Delivered'),
(0, '2015-03-04', 'PMapping', 'Delivered'),
(0, '2015-03-04', 'PService', 'Delivered'),
(0, '2015-03-04', 'PriceList', 'Delivered'),
(0, '2015-03-04', 'RCategory', 'Delivered'),
(0, '2015-03-04', 'VMake', 'Delivered'),
(0, '2015-03-04', 'VSegment', 'Delivered'),
(0, '2015-03-04', 'VModel', 'Delivered'),
(0, '2015-03-04', 'FMode', 'Delivered'),
(0, '2015-03-04', 'Scheme', 'Delivered'),
(0, '2015-03-04', 'OEM', 'Delivered'),
(0, '2015-03-05', 'product Group', 'Delivered'),
(0, '2015-03-05', 'PSegment', 'Delivered'),
(0, '2015-03-05', 'PType', 'Delivered'),
(0, '2015-03-05', 'PUOM', 'Delivered'),
(0, '2015-03-05', 'Product', 'Delivered'),
(0, '2015-03-05', 'PWarranty', 'Delivered'),
(0, '2015-03-05', 'PMapping', 'Delivered'),
(0, '2015-03-05', 'PService', 'Delivered'),
(0, '2015-03-05', 'PriceList', 'Delivered'),
(0, '2015-03-05', 'RCategory', 'Delivered'),
(0, '2015-03-05', 'VMake', 'Delivered'),
(0, '2015-03-05', 'VSegment', 'Delivered'),
(0, '2015-03-05', 'VModel', 'Delivered'),
(0, '2015-03-05', 'FMode', 'Delivered'),
(0, '2015-03-05', 'Scheme', 'Delivered'),
(0, '2015-03-05', 'OEM', 'Delivered'),
(1432434, '2015-03-16', 'PSegment', 'Delivered'),
(1432434, '2015-03-16', 'PType', 'Delivered'),
(1432434, '2015-03-16', 'PUOM', 'Delivered'),
(1432434, '2015-03-16', 'Product', 'Delivered'),
(1432434, '2015-03-16', 'PWarranty', 'Delivered'),
(1432434, '2015-03-16', 'PMapping', 'Delivered'),
(1432434, '2015-03-16', 'PService', 'Delivered'),
(1432434, '2015-03-16', 'PriceList', 'Delivered'),
(1432434, '2015-03-16', 'RCategory', 'Delivered'),
(1432434, '2015-03-16', 'VMake', 'Delivered'),
(1432434, '2015-03-16', 'VSegment', 'Delivered'),
(1432434, '2015-03-16', 'VModel', 'Delivered'),
(1432434, '2015-03-16', 'FMode', 'Delivered'),
(1432434, '2015-03-16', 'Scheme', 'Delivered'),
(1432434, '2015-03-16', 'OEM', 'Delivered'),
(1432434, '2015-03-16', 'product Group', 'Delivered'),
(1432434, '2015-03-16', 'PSegment', 'Delivered'),
(1432434, '2015-03-16', 'PType', 'Delivered'),
(1432434, '2015-03-16', 'PUOM', 'Delivered'),
(1432434, '2015-03-16', 'Product', 'Delivered'),
(1432434, '2015-03-16', 'PWarranty', 'Delivered'),
(1432434, '2015-03-16', 'PMapping', 'Delivered'),
(1432434, '2015-03-16', 'PService', 'Delivered'),
(1432434, '2015-03-16', 'PriceList', 'Delivered'),
(1432434, '2015-03-16', 'RCategory', 'Delivered'),
(1432434, '2015-03-16', 'VMake', 'Delivered'),
(1432434, '2015-03-16', 'VSegment', 'Delivered'),
(1432434, '2015-03-16', 'VModel', 'Delivered'),
(1432434, '2015-03-16', 'FMode', 'Delivered'),
(1432434, '2015-03-16', 'Scheme', 'Delivered'),
(1432434, '2015-03-16', 'OEM', 'Delivered'),
(6351019, '2015-04-06', 'product Group', 'Delivered'),
(6351019, '2015-04-06', 'PSegment', 'Delivered'),
(6351019, '2015-04-06', 'PType', 'Delivered'),
(6351019, '2015-04-06', 'PUOM', 'Delivered'),
(6351019, '2015-04-06', 'Product', 'Delivered'),
(6351019, '2015-04-06', 'PWarranty', 'Delivered'),
(6351019, '2015-04-06', 'PMapping', 'Delivered'),
(6351019, '2015-04-06', 'PService', 'Delivered'),
(6351019, '2015-04-06', 'PriceList', 'Delivered'),
(6351019, '2015-04-06', 'RCategory', 'Delivered'),
(6351019, '2015-04-06', 'VMake', 'Delivered'),
(6351019, '2015-04-06', 'VSegment', 'Delivered'),
(6351019, '2015-04-06', 'VModel', 'Delivered'),
(6351019, '2015-04-06', 'FMode', 'Delivered'),
(6351019, '2015-04-06', 'Scheme', 'Delivered'),
(6351019, '2015-04-06', 'OEM', 'Delivered');

-- --------------------------------------------------------

--
-- Table structure for table `employeemaster`
--

CREATE TABLE IF NOT EXISTS `employeemaster` (
  `employeecode` varchar(50) NOT NULL DEFAULT '',
  `employeename` varchar(50) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `designation` varchar(50) DEFAULT NULL,
  `country` varchar(20) DEFAULT NULL,
  `region` varchar(20) DEFAULT NULL,
  `state` varchar(20) DEFAULT NULL,
  `branch` varchar(20) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `m_date` datetime DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`employeecode`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

--
-- Dumping data for table `employeemaster`
--

INSERT INTO `employeemaster` (`employeecode`, `employeename`, `address`, `contact`, `email`, `designation`, `country`, `region`, `state`, `branch`, `user_id`, `m_date`, `id`) VALUES
('a', 'a', 'a', '9629914707', 'pv@gmail.com', 'Manager', 'IN', 'South2', '22', '1015', 'admin', '2015-03-26 10:04:13', 38),
('E01', 'Tiara', 'CHENNAI', '9942140645', 'sairammanigandan.km@tiaraconsulting.com', 'IT Analyst', 'IN', 'South2', '22', '1015', 'admin', '2015-03-10 10:05:35', 2),
('tally', 'Tally', 'Bangalore', '9907766696', 'Tally@tallysolutions.com', 'Manager', 'IN', 'South1', '10', '1006', 'admin', '2015-04-09 14:44:35', 37),
('tiara', 'TIARA', 'Chennai', '9500077223', 'premnath@tiaraconsulting.com', 'BDM', 'IN', 'South2', '22', '1015', 'admin', '2014-11-06 11:59:25', 35);

-- --------------------------------------------------------

--
-- Table structure for table `failuremode`
--

CREATE TABLE IF NOT EXISTS `failuremode` (
  `failuremode` varchar(15) NOT NULL DEFAULT '',
  `failuremodedescription` varchar(50) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `m_date` datetime DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`failuremode`),
  KEY `Id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `franchisemaster`
--

CREATE TABLE IF NOT EXISTS `franchisemaster` (
  `Franchisecode` varchar(50) NOT NULL DEFAULT '',
  `Franchisename` varchar(50) DEFAULT NULL,
  `ContactPerson` varchar(50) DEFAULT NULL,
  `Designation` varchar(50) DEFAULT NULL,
  `Address` longtext,
  `Pincode` varchar(30) DEFAULT NULL,
  `TelephoneNo` varchar(30) DEFAULT NULL,
  `MobileNo` varchar(30) DEFAULT NULL,
  `Email` varchar(70) DEFAULT NULL,
  `Branch` varchar(50) DEFAULT NULL,
  `State` varchar(50) DEFAULT NULL,
  `Region` varchar(50) DEFAULT NULL,
  `Country` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `tinno` varchar(50) DEFAULT NULL,
  `cstno` varchar(50) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `m_date` datetime DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`Franchisecode`),
  KEY `Id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `franchisemaster`
--

INSERT INTO `franchisemaster` (`Franchisecode`, `Franchisename`, `ContactPerson`, `Designation`, `Address`, `Pincode`, `TelephoneNo`, `MobileNo`, `Email`, `Branch`, `State`, `Region`, `Country`, `city`, `tinno`, `cstno`, `user_id`, `m_date`, `id`) VALUES
('1000681', 'SRI GANESH AUTO PARTS CENTRE', 'GANESH', 'OWNER', 'SHOP NO-1, N-10 KALKAJI,NEW DELHI', '110019', '044-9878451', '9897746464', 'raj@gmail.com', '1020', '30', 'North1', 'IN', 'Delhi', 'TN9089765', 'CST452', 'admin', '2015-02-10 15:20:53', 1),
('1432434', 'RAM  AND  CO', 'RAMSHANKAR', 'OWNER', '4/5,MANJUNATHA BUILDING,KRISHNAGIRI BYE PASS ROAD,HOSUR,', '635109', '022-544545', '9397746465', 'poorna45@hotmail.com', '1016', '22', 'South2', 'IN', 'Coimbatore', 'TN9089790', 'CST89', 'admin', '2015-03-16 11:06:39', 5),
('6351019', 'ABC AGENCIES', 'RAMSHANKAR', 'OWNER', '4/5,MANJUNATHA BUILDING,KRISHNAGIRI BYE PASS ROAD,HOSUR,', '635109', '022-544545', '9397746465', 'poorna45@hotmail.com', '1016', '22', 'South2', 'IN', 'Coimbatore', 'TN9089790', 'CST89', 'admin', '2015-02-10 15:32:22', 2),
('RAC9991', 'GEORGE ENTERPRISES', 'DHAMODHARAN', 'OWNER', '15, NELSON MANICKAM ROAD, CHOOLAIMEDU', '600094', '0442324132', '9500077223', 'dhamu@gmail.com', '1015', '22', 'South2', 'IN', '', '', '', 'admin', '2015-03-04 12:57:39', 4),
('RAC9992', 'SARAVANA STORES', 'SARAVANAN', 'OWNER', '33, RANGANATHAN STREET, T.NAGAR', '600017', '0442324133', '9500077223', 'saravanan@gmail.com', '1015', '22', 'South2', 'IN', '', '', '', 'admin', '2015-03-04 12:56:00', 3);

-- --------------------------------------------------------

--
-- Table structure for table `hyundaiday`
--

CREATE TABLE IF NOT EXISTS `hyundaiday` (
  `hcode` varchar(15) NOT NULL DEFAULT '',
  `hvalue` int(15) DEFAULT NULL,
  `id` int(15) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`hcode`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

--
-- Dumping data for table `hyundaiday`
--

INSERT INTO `hyundaiday` (`hcode`, `hvalue`, `id`) VALUES
('1', 27, 27),
('2', 28, 28),
('3', 29, 29),
('4', 30, 30),
('5', 31, 31),
('A', 1, 1),
('B', 2, 2),
('C', 3, 3),
('D', 4, 4),
('E', 5, 5),
('F', 6, 6),
('G', 7, 7),
('H', 8, 8),
('I', 9, 9),
('J', 10, 10),
('K', 11, 11),
('L', 12, 12),
('M', 13, 13),
('N', 14, 14),
('O', 15, 15),
('P', 16, 16),
('Q', 17, 17),
('R', 18, 18),
('S', 19, 19),
('T', 20, 20),
('U', 21, 21),
('V', 22, 22),
('W', 23, 23),
('X', 24, 24),
('Y', 25, 25),
('Z', 26, 26);

-- --------------------------------------------------------

--
-- Table structure for table `logicmaster`
--

CREATE TABLE IF NOT EXISTS `logicmaster` (
  `category` varchar(50) DEFAULT NULL,
  `logiccode` varchar(50) DEFAULT NULL,
  `effectivedate` date DEFAULT NULL,
  `minimum` int(15) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `m_date` datetime DEFAULT NULL,
  `id` int(50) NOT NULL AUTO_INCREMENT,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `masterpricelist`
--

CREATE TABLE IF NOT EXISTS `masterpricelist` (
  `pricelistcode` varchar(50) NOT NULL DEFAULT '',
  `pricelistname` varchar(50) DEFAULT NULL,
  `effectivedate` date DEFAULT NULL,
  `applicabledate` date DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `m_date` datetime DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`pricelistcode`),
  KEY `Id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `masterpricelist`
--

INSERT INTO `masterpricelist` (`pricelistcode`, `pricelistname`, `effectivedate`, `applicabledate`, `user_id`, `m_date`, `id`) VALUES
('MRP-D001', 'AMR-PRICELIST-001', '2014-06-03', '2014-06-29', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `masterproduct`
--

CREATE TABLE IF NOT EXISTS `masterproduct` (
  `ProductCode` varchar(50) DEFAULT NULL,
  `ProductDescription` varchar(100) DEFAULT NULL,
  `ProductType` varchar(50) DEFAULT NULL,
  `warrantyapplicable` varchar(5) DEFAULT NULL,
  `EnableSerialno` int(10) DEFAULT NULL,
  `IdentificationCode` varchar(50) DEFAULT NULL,
  `Status` varchar(50) DEFAULT NULL,
  `UOM` varchar(10) DEFAULT NULL,
  `SalesType` varchar(50) DEFAULT NULL,
  `proratalogic` varchar(50) DEFAULT NULL,
  `logic` varchar(50) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `m_date` datetime DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  KEY `Id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `masterproduct`
--

INSERT INTO `masterproduct` (`ProductCode`, `ProductDescription`, `ProductType`, `warrantyapplicable`, `EnableSerialno`, `IdentificationCode`, `Status`, `UOM`, `SalesType`, `proratalogic`, `logic`, `user_id`, `m_date`, `id`) VALUES
('PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', 'PTC1', 'NO', 0, '', 'Active', 'NOS', 'AFTER MARKET', '', '', 'admin', '2015-02-10 15:31:25', 1),
('PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', 'PTC2', 'NO', 0, '', 'Active', 'NOS', 'AFTER MARKET', '', '', 'admin', '2015-02-10 15:31:25', 2),
('RAC001', 'ETERNO DG(15L AND 25L)', 'PTC1', 'NO', 0, '', 'Active', 'NOS', 'AFTER MARKET', '', '', 'admin', '2015-03-04 14:37:21', 3),
('RAC002', 'ETERNO 2(10L-35L)', 'PTC2', 'NO', 0, '', 'Active', 'NOS', 'AFTER MARKET', '', '', 'admin', '2015-03-04 14:37:21', 4),
('RAC004', 'ALTRO 2(15L-50L)', 'PTC2', 'NO', 0, '', 'Active', 'NOS', 'AFTER MARKET', '', '', 'admin', '2015-03-04 14:37:21', 5),
('RAC006', 'CDR', 'PTC2', 'NO', 0, '', 'Active', 'NOS', 'AFTER MARKET', '', '', 'admin', '2015-03-04 14:37:22', 6),
('RAC007', 'PRONTO(6L)', 'PTC1', 'NO', 0, '', 'Active', 'NOS', 'AFTER MARKET', '', '', 'admin', '2015-03-04 14:37:22', 7),
('RAC008', 'PLATINUM(50L-100L)', 'PTC2', 'NO', 0, '', 'Active', 'NOS', 'AFTER MARKET', '', '', 'admin', '2015-03-04 14:37:23', 8),
('RAC009', 'PLATINUM(150L-300L)', 'PTC1', 'NO', 0, '', 'Active', 'NOS', 'AFTER MARKET', '', '', 'admin', '2015-03-04 14:37:23', 9),
('RAC010', 'PRONTO(1L AND 3L)', 'PTC2', 'NO', 0, '', 'Active', 'NOS', 'AFTER MARKET', '', '', 'admin', '2015-03-04 14:37:23', 10),
('RAC011', 'NATURAL FLUE', 'PTC1', 'NO', 0, '', 'Active', 'NOS', 'AFTER MARKET', '', '', 'admin', '2015-03-04 14:37:24', 11),
('RAC012', 'OMEGA MAX 8', 'PTC2', 'NO', 0, '', 'Active', 'NOS', 'AFTER MARKET', '', '', 'admin', '2015-03-04 14:37:24', 12),
('RAC013', 'OMEGA', 'PTC1', 'NO', 0, '', 'Active', 'NOS', 'AFTER MARKET', '', '', 'admin', '2015-03-04 14:37:25', 13),
('RAC014', 'ALPHA', 'PTC2', 'NO', 0, '', 'Active', 'NOS', 'AFTER MARKET', '', '', 'admin', '2015-03-04 14:37:25', 14),
('RAC015', 'SOLAR COMMERCIAL', 'PTC1', 'NO', 0, '', 'Active', 'NOS', 'AFTER MARKET', '', '', 'admin', '2015-03-04 14:37:26', 15),
('RAC016', 'HEAT PUMP', 'PTC2', 'NO', 0, '', 'Active', 'NOS', 'AFTER MARKET', '', '', 'admin', '2015-03-04 14:37:26', 16);

-- --------------------------------------------------------

--
-- Table structure for table `monthlogic`
--

CREATE TABLE IF NOT EXISTS `monthlogic` (
  `code` varchar(15) NOT NULL DEFAULT '',
  `value` int(15) DEFAULT NULL,
  `id` int(15) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`code`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `monthlogic`
--

INSERT INTO `monthlogic` (`code`, `value`, `id`) VALUES
('A', 1, 1),
('B', 2, 2),
('C', 3, 3),
('D', 4, 4),
('E', 5, 5),
('F', 6, 6),
('G', 7, 7),
('H', 8, 8),
('I', 9, 9),
('J', 10, 10),
('K', 11, 11),
('L', 12, 12);

-- --------------------------------------------------------

--
-- Table structure for table `oemmaster`
--

CREATE TABLE IF NOT EXISTS `oemmaster` (
  `oemcode` varchar(50) NOT NULL,
  `oemname` varchar(50) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `m_date` datetime DEFAULT NULL,
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oemcodes` varchar(100) NOT NULL,
  PRIMARY KEY (`oemcode`),
  KEY `id` (`id`),
  KEY `id_2` (`id`),
  KEY `oemname` (`oemname`),
  KEY `oemname_2` (`oemname`),
  KEY `oemname_3` (`oemname`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `oemmaster`
--

INSERT INTO `oemmaster` (`oemcode`, `oemname`, `user_id`, `m_date`, `id`, `oemcodes`) VALUES
('OEMC1', 'OEM SAMPLE 1', 'admin', '2014-09-18 11:02:15', 1, ''),
('OEMC2', 'OEM SAMPLE 2', 'admin', '2015-03-31 13:16:43', 2, '');

-- --------------------------------------------------------

--
-- Table structure for table `oemmasterupload`
--

CREATE TABLE IF NOT EXISTS `oemmasterupload` (
  `Franchiseecode` varchar(30) DEFAULT NULL,
  `Masters` varchar(50) DEFAULT NULL,
  `Code` varchar(50) NOT NULL,
  `Status` varchar(30) DEFAULT NULL,
  `InsertDate` date DEFAULT NULL,
  `Deliverydae` date DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `oemmasterupload`
--

INSERT INTO `oemmasterupload` (`Franchiseecode`, `Masters`, `Code`, `Status`, `InsertDate`, `Deliverydae`, `id`) VALUES
('1000681', 'oemmaster', 'OEMC1', '0', '2015-02-10', '2015-02-10', 1),
('1000681', 'oemmaster', 'OEMC2', '0', '2015-02-10', '2015-02-10', 2),
('6351019', 'oemmaster', 'OEMC1', '2', '2015-02-10', '2015-04-06', 3),
('6351019', 'oemmaster', 'OEMC2', '2', '2015-02-10', '2015-04-06', 4),
('RAC9992', 'oemmaster', 'OEMC1', '2', '2015-03-04', '2015-03-04', 5),
('RAC9992', 'oemmaster', 'OEMC2', '1', '2015-03-04', '2015-03-04', 6),
('RAC9991', 'oemmaster', 'OEMC1', '2', '2015-03-04', '2015-03-04', 7),
('RAC9991', 'oemmaster', 'OEMC2', '1', '2015-03-04', '2015-03-04', 8),
('1000681', 'oemmaster', 'OEMC1', '0', '2015-03-16', '2015-03-16', 9),
('1000681', 'oemmaster', 'OEMC2', '0', '2015-03-16', '2015-03-16', 10),
('6351019', 'oemmaster', 'OEMC1', '2', '2015-03-16', '2015-04-06', 11),
('6351019', 'oemmaster', 'OEMC2', '2', '2015-03-16', '2015-04-06', 12),
('1432434', 'oemmaster', 'OEMC1', '2', '2015-03-16', '2015-03-16', 13),
('1432434', 'oemmaster', 'OEMC2', '1', '2015-03-16', '2015-03-16', 14);

-- --------------------------------------------------------

--
-- Stand-in structure for view `oemupload`
--
CREATE TABLE IF NOT EXISTS `oemupload` (
`oemcode` varchar(50)
,`oemname` varchar(50)
,`Status` varchar(30)
,`Franchiseecode` varchar(30)
);
-- --------------------------------------------------------

--
-- Table structure for table `oldlogic`
--

CREATE TABLE IF NOT EXISTS `oldlogic` (
  `lcode` varchar(15) NOT NULL DEFAULT '',
  `lvalues` int(15) DEFAULT NULL,
  `id` int(15) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`lcode`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

--
-- Dumping data for table `oldlogic`
--

INSERT INTO `oldlogic` (`lcode`, `lvalues`, `id`) VALUES
('0', 2000, 1),
('1', 2001, 2),
('2', 2002, 5),
('3', 2003, 6),
('4', 2004, 7),
('5', 2005, 8),
('6', 2006, 9),
('7', 2007, 10),
('8', 2008, 11),
('9', 2009, 12),
('A', 2010, 13),
('B', 2011, 14),
('C', 2012, 15),
('D', 2013, 16),
('E', 2014, 17),
('F', 2015, 18),
('G', 2016, 19),
('H', 2017, 20),
('I', 2018, 21),
('J', 2019, 22),
('K', 2020, 23),
('L', 2021, 24),
('M', 2022, 25),
('N', 2023, 26),
('O', 2024, 27),
('P', 2025, 28),
('Q', 2026, 29),
('R', 2027, 30),
('S', 2028, 31),
('T', 2029, 32),
('U', 2030, 33),
('V', 2031, 34),
('W', 2032, 35),
('X', 2033, 36),
('Y', 2034, 37),
('Z', 2035, 38);

-- --------------------------------------------------------

--
-- Table structure for table `pagination`
--

CREATE TABLE IF NOT EXISTS `pagination` (
  `page` int(10) NOT NULL,
  `configure` varchar(50) NOT NULL,
  `timeoutvar` int(10) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `m_date` datetime NOT NULL,
  `nopass` int(20) NOT NULL,
  `id` int(50) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `pagination`
--

INSERT INTO `pagination` (`page`, `configure`, `timeoutvar`, `user_id`, `m_date`, `nopass`, `id`) VALUES
(20, 'dhamodharan@tiaraconsulting.com', 50, 'admin', '2015-04-09 14:42:31', 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pricelistlinking`
--

CREATE TABLE IF NOT EXISTS `pricelistlinking` (
  `PriceListCode` varchar(100) DEFAULT NULL,
  `pricelistname` varchar(100) DEFAULT NULL,
  `Franchisee` varchar(100) DEFAULT NULL,
  `Country` varchar(100) DEFAULT NULL,
  `State` varchar(100) DEFAULT NULL,
  `Branch` varchar(100) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `m_date` datetime DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  KEY `Id` (`id`),
  KEY `index_2` (`PriceListCode`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=3 ;

--
-- Dumping data for table `pricelistlinking`
--

INSERT INTO `pricelistlinking` (`PriceListCode`, `pricelistname`, `Franchisee`, `Country`, `State`, `Branch`, `user_id`, `m_date`, `id`) VALUES
('MRP-D001', 'AMR-PRICELIST-001', '1000681', 'IN', '30', '1020', 'admin', '2015-02-10 15:38:09', 1),
('MRP-D001', 'AMR-PRICELIST-001', '6351019', 'IN', '22', '1016', 'admin', '2015-02-10 15:38:10', 2);

-- --------------------------------------------------------

--
-- Table structure for table `pricelistlinkinggrid`
--

CREATE TABLE IF NOT EXISTS `pricelistlinkinggrid` (
  `PriceListCode` varchar(100) DEFAULT NULL,
  `pricelistname` varchar(100) DEFAULT NULL,
  `Country` varchar(100) DEFAULT NULL,
  `State` varchar(100) DEFAULT NULL,
  `Branch` varchar(100) DEFAULT NULL,
  `Franchisee` varchar(100) DEFAULT NULL,
  `effectivedate` date DEFAULT NULL,
  `applicabledate` date DEFAULT NULL,
  `productcode` varchar(50) DEFAULT NULL,
  `mrp` int(20) DEFAULT NULL,
  `fprice` int(20) DEFAULT NULL,
  `rprice` int(20) DEFAULT NULL,
  `iprice` int(20) DEFAULT NULL,
  `Status` varchar(30) DEFAULT NULL,
  `InsertDate` date DEFAULT NULL,
  `Deliverydae` date DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  KEY `Id` (`id`),
  KEY `index_2` (`PriceListCode`,`Franchisee`,`productcode`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=5 ;

--
-- Dumping data for table `pricelistlinkinggrid`
--

INSERT INTO `pricelistlinkinggrid` (`PriceListCode`, `pricelistname`, `Country`, `State`, `Branch`, `Franchisee`, `effectivedate`, `applicabledate`, `productcode`, `mrp`, `fprice`, `rprice`, `iprice`, `Status`, `InsertDate`, `Deliverydae`, `id`) VALUES
('MRP-D001', 'AMR-PRICELIST-001', 'IN', '30', '1020', '1000681', '2014-06-03', '2014-06-29', 'PRODUCT30', 2901, 3176, 4401, 2098, '0', '2015-02-10', '2015-02-10', 1),
('MRP-D001', 'AMR-PRICELIST-001', 'IN', '30', '1020', '1000681', '2014-06-03', '2014-06-29', 'PRODUCT40', 2901, 3176, 4401, 2098, '0', '2015-02-10', '2015-02-10', 2),
('MRP-D001', 'AMR-PRICELIST-001', 'IN', '22', '1016', '6351019', '2014-06-03', '2014-06-29', 'PRODUCT30', 2901, 3176, 4401, 2098, '2', '2015-02-10', '2015-02-10', 3),
('MRP-D001', 'AMR-PRICELIST-001', 'IN', '22', '1016', '6351019', '2014-06-03', '2014-06-29', 'PRODUCT40', 2901, 3176, 4401, 2098, '2', '2015-02-10', '2015-02-10', 4);

-- --------------------------------------------------------

--
-- Stand-in structure for view `pricelistlinkinggrid_view`
--
CREATE TABLE IF NOT EXISTS `pricelistlinkinggrid_view` (
`PriceListCode` varchar(100)
,`pricelistname` varchar(100)
,`Country` varchar(100)
,`State` varchar(100)
,`Branch` varchar(100)
,`Franchisee` varchar(100)
,`effectivedate` date
,`applicabledate` date
,`productcode` varchar(50)
,`productdescription` varchar(100)
,`mrp` int(20)
,`fprice` int(20)
,`rprice` int(20)
,`iprice` int(20)
,`Status` varchar(30)
,`InsertDate` date
,`Deliverydae` date
,`Id` int(11)
);
-- --------------------------------------------------------

--
-- Table structure for table `pricelistmaster`
--

CREATE TABLE IF NOT EXISTS `pricelistmaster` (
  `pricelistcode` varchar(50) DEFAULT NULL,
  `pricelistname` varchar(50) DEFAULT NULL,
  `effectivedate` date DEFAULT NULL,
  `applicabledate` date DEFAULT NULL,
  `productcode` varchar(50) DEFAULT NULL,
  `mrp` float DEFAULT NULL,
  `fprice` float DEFAULT NULL,
  `rprice` float DEFAULT NULL,
  `iprice` float DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  KEY `ID` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `pricelistmaster`
--

INSERT INTO `pricelistmaster` (`pricelistcode`, `pricelistname`, `effectivedate`, `applicabledate`, `productcode`, `mrp`, `fprice`, `rprice`, `iprice`, `id`) VALUES
('MRP-D001', 'AMR-PRICELIST-001', '2014-06-03', '2014-06-29', 'PRODUCT30', 2901, 3176, 4401, 2098, 1),
('MRP-D001', 'AMR-PRICELIST-001', '2014-06-03', '2014-06-29', 'PRODUCT40', 2901, 3176, 4401, 2098, 2);

-- --------------------------------------------------------

--
-- Table structure for table `productfailuremodeupload`
--

CREATE TABLE IF NOT EXISTS `productfailuremodeupload` (
  `Franchiseecode` varchar(30) DEFAULT NULL,
  `Masters` varchar(50) DEFAULT NULL,
  `Code` varchar(100) DEFAULT NULL,
  `Status` varchar(30) DEFAULT NULL,
  `InsertDate` date DEFAULT NULL,
  `Deliverydae` date DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `productgroupmaster`
--

CREATE TABLE IF NOT EXISTS `productgroupmaster` (
  `ProductCode` varchar(50) NOT NULL DEFAULT '',
  `ProductGroup` varchar(50) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `m_date` datetime DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ProductCode`),
  UNIQUE KEY `ProductGroup` (`ProductGroup`),
  KEY `ID` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `productgroupmaster`
--

INSERT INTO `productgroupmaster` (`ProductCode`, `ProductGroup`, `user_id`, `m_date`, `id`) VALUES
('PGC1', 'PRODUCT GROUP SAMPLE 1', 'admin', '2014-09-18 11:04:47', 1),
('PGC2', 'PRODUCT GROUP SAMPLE 2', 'admin', '2014-09-18 11:05:07', 2);

-- --------------------------------------------------------

--
-- Table structure for table `productgroupupload`
--

CREATE TABLE IF NOT EXISTS `productgroupupload` (
  `Franchiseecode` varchar(30) DEFAULT NULL,
  `Masters` varchar(50) DEFAULT NULL,
  `Code` varchar(100) DEFAULT NULL,
  `Status` varchar(30) DEFAULT NULL,
  `InsertDate` date DEFAULT NULL,
  `Deliverydae` date DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `productgroupupload`
--

INSERT INTO `productgroupupload` (`Franchiseecode`, `Masters`, `Code`, `Status`, `InsertDate`, `Deliverydae`, `id`) VALUES
('1000681', 'productgroupmaster', 'PGC1', '0', '2015-02-10', '2015-02-10', 1),
('1000681', 'productgroupmaster', 'PGC2', '0', '2015-02-10', '2015-02-10', 2),
('6351019', 'productgroupmaster', 'PGC1', '2', '2015-02-10', '2015-02-10', 3),
('6351019', 'productgroupmaster', 'PGC2', '2', '2015-02-10', '2015-02-10', 4),
('RAC9992', 'productgroupmaster', 'PGC1', '0', '2015-03-04', '2015-03-04', 5),
('RAC9992', 'productgroupmaster', 'PGC2', '0', '2015-03-04', '2015-03-04', 6),
('RAC9991', 'productgroupmaster', 'PGC1', '0', '2015-03-04', '2015-03-04', 7),
('RAC9991', 'productgroupmaster', 'PGC2', '0', '2015-03-04', '2015-03-04', 8),
('1000681', 'productgroupmaster', 'PGC1', '0', '2015-03-16', '2015-03-16', 9),
('1000681', 'productgroupmaster', 'PGC2', '0', '2015-03-16', '2015-03-16', 10),
('6351019', 'productgroupmaster', 'PGC1', '0', '2015-03-16', '2015-03-16', 11),
('6351019', 'productgroupmaster', 'PGC2', '0', '2015-03-16', '2015-03-16', 12),
('1432434', 'productgroupmaster', 'PGC1', '0', '2015-03-16', '2015-03-16', 13),
('1432434', 'productgroupmaster', 'PGC2', '0', '2015-03-16', '2015-03-16', 14);

-- --------------------------------------------------------

--
-- Table structure for table `productmapping`
--

CREATE TABLE IF NOT EXISTS `productmapping` (
  `ProductCode` varchar(50) DEFAULT NULL,
  `MapProductCode` varchar(50) DEFAULT NULL,
  `effectivedate` date DEFAULT NULL,
  `Status` varchar(20) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `m_date` datetime DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  KEY `ID` (`id`),
  KEY `ID_2` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `productmappingupload`
--

CREATE TABLE IF NOT EXISTS `productmappingupload` (
  `Franchiseecode` varchar(30) DEFAULT NULL,
  `Masters` varchar(50) DEFAULT NULL,
  `Code` varchar(100) DEFAULT NULL,
  `Status` varchar(30) DEFAULT NULL,
  `InsertDate` date DEFAULT NULL,
  `Deliverydae` date DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `productmapping_view`
--
CREATE TABLE IF NOT EXISTS `productmapping_view` (
`ProductDescription` varchar(100)
,`ProductCode` varchar(50)
,`MapProductCode` varchar(50)
,`effectivedate` date
,`Franchiseecode` varchar(30)
,`Status` varchar(30)
,`pstatus` varchar(20)
);
-- --------------------------------------------------------

--
-- Table structure for table `productmaster`
--

CREATE TABLE IF NOT EXISTS `productmaster` (
  `ProductCode` varchar(50) NOT NULL DEFAULT '',
  `ProductDescription` varchar(100) DEFAULT NULL,
  `ProductType` varchar(50) DEFAULT NULL,
  `warrantyapplicable` varchar(5) DEFAULT NULL,
  `Status` varchar(50) DEFAULT NULL,
  `UOM` varchar(10) DEFAULT NULL,
  `SalesType` varchar(50) DEFAULT NULL,
  `proratalogic` varchar(50) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `m_date` datetime DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ProductCode`),
  KEY `Id` (`id`),
  KEY `ProductDescription` (`ProductDescription`),
  KEY `index_4` (`ProductType`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=17 ;

--
-- Dumping data for table `productmaster`
--

INSERT INTO `productmaster` (`ProductCode`, `ProductDescription`, `ProductType`, `warrantyapplicable`, `Status`, `UOM`, `SalesType`, `proratalogic`, `user_id`, `m_date`, `id`) VALUES
('PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', 'PTC1', 'NO', 'Active', 'NOS', 'AFTER MARKET', '', 'admin', '2015-02-10 15:31:25', 1),
('PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', 'PTC2', 'NO', 'Active', 'NOS', 'AFTER MARKET', '', 'admin', '2015-02-10 15:31:25', 2),
('RAC001', 'ETERNO DG(15L AND 25L)', 'PTC1', 'NO', 'Active', 'NOS', 'AFTER MARKET', '', 'admin', '2015-03-04 14:37:21', 3),
('RAC002', 'ETERNO 2(10L-35L)', 'PTC2', 'NO', 'Active', 'NOS', 'AFTER MARKET', '', 'admin', '2015-03-04 14:37:21', 4),
('RAC004', 'ALTRO 2(15L-50L)', 'PTC2', 'NO', 'Active', 'NOS', 'AFTER MARKET', '', 'admin', '2015-03-04 14:37:21', 5),
('RAC006', 'CDR', 'PTC2', 'NO', 'Active', 'NOS', 'AFTER MARKET', '', 'admin', '2015-03-04 14:37:22', 6),
('RAC007', 'PRONTO(6L)', 'PTC1', 'NO', 'Active', 'NOS', 'AFTER MARKET', '', 'admin', '2015-03-04 14:37:22', 7),
('RAC008', 'PLATINUM(50L-100L)', 'PTC2', 'NO', 'Active', 'NOS', 'AFTER MARKET', '', 'admin', '2015-03-04 14:37:23', 8),
('RAC009', 'PLATINUM(150L-300L)', 'PTC1', 'NO', 'Active', 'NOS', 'AFTER MARKET', '', 'admin', '2015-03-04 14:37:23', 9),
('RAC010', 'PRONTO(1L AND 3L)', 'PTC2', 'NO', 'Active', 'NOS', 'AFTER MARKET', '', 'admin', '2015-03-04 14:37:23', 10),
('RAC011', 'NATURAL FLUE', 'PTC1', 'NO', 'Active', 'NOS', 'AFTER MARKET', '', 'admin', '2015-03-04 14:37:24', 11),
('RAC012', 'OMEGA MAX 8', 'PTC2', 'NO', 'Active', 'NOS', 'AFTER MARKET', '', 'admin', '2015-03-04 14:37:24', 12),
('RAC013', 'OMEGA', 'PTC1', 'NO', 'Active', 'NOS', 'AFTER MARKET', '', 'admin', '2015-03-04 14:37:25', 13),
('RAC014', 'ALPHA', 'PTC2', 'NO', 'Active', 'NOS', 'AFTER MARKET', '', 'admin', '2015-03-04 14:37:25', 14),
('RAC015', 'SOLAR COMMERCIAL', 'PTC1', 'NO', 'Active', 'NOS', 'AFTER MARKET', '', 'admin', '2015-03-04 14:37:26', 15),
('RAC016', 'HEAT PUMP', 'PTC2', 'NO', 'Active', 'NOS', 'AFTER MARKET', '', 'admin', '2015-03-04 14:37:26', 16);

-- --------------------------------------------------------

--
-- Table structure for table `productmasterupload`
--

CREATE TABLE IF NOT EXISTS `productmasterupload` (
  `Franchiseecode` varchar(30) DEFAULT NULL,
  `Masters` varchar(50) DEFAULT NULL,
  `Code` varchar(100) DEFAULT NULL,
  `Status` varchar(30) DEFAULT NULL,
  `InsertDate` date DEFAULT NULL,
  `Deliverydae` date DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=113 ;

--
-- Dumping data for table `productmasterupload`
--

INSERT INTO `productmasterupload` (`Franchiseecode`, `Masters`, `Code`, `Status`, `InsertDate`, `Deliverydae`, `id`) VALUES
('1000681', 'productmaster', 'PRODUCT30', '0', '2015-02-10', '2015-02-10', 1),
('6351019', 'productmaster', 'PRODUCT30', '2', '2015-02-10', '2015-04-06', 2),
('1000681', 'productmaster', 'PRODUCT40', '0', '2015-02-10', '2015-02-10', 3),
('6351019', 'productmaster', 'PRODUCT40', '2', '2015-02-10', '2015-04-06', 4),
('RAC9992', 'productmaster', 'PRODUCT30', '2', '2015-03-04', '2015-03-05', 5),
('RAC9992', 'productmaster', 'PRODUCT40', '2', '2015-03-04', '2015-03-05', 6),
('RAC9991', 'productmaster', 'PRODUCT30', '2', '2015-03-04', '2015-03-04', 7),
('RAC9991', 'productmaster', 'PRODUCT40', '2', '2015-03-04', '2015-03-04', 8),
('1000681', 'productmaster', 'RAC001', '0', '2015-03-04', '2015-03-04', 9),
('6351019', 'productmaster', 'RAC001', '2', '2015-03-04', '2015-04-06', 10),
('RAC9992', 'productmaster', 'RAC001', '2', '2015-03-04', '2015-03-05', 11),
('RAC9991', 'productmaster', 'RAC001', '2', '2015-03-04', '2015-03-04', 12),
('1000681', 'productmaster', 'RAC002', '0', '2015-03-04', '2015-03-04', 13),
('6351019', 'productmaster', 'RAC002', '2', '2015-03-04', '2015-04-06', 14),
('RAC9992', 'productmaster', 'RAC002', '2', '2015-03-04', '2015-03-05', 15),
('RAC9991', 'productmaster', 'RAC002', '2', '2015-03-04', '2015-03-04', 16),
('1000681', 'productmaster', 'RAC004', '0', '2015-03-04', '2015-03-04', 17),
('6351019', 'productmaster', 'RAC004', '2', '2015-03-04', '2015-04-06', 18),
('RAC9992', 'productmaster', 'RAC004', '2', '2015-03-04', '2015-03-05', 19),
('RAC9991', 'productmaster', 'RAC004', '2', '2015-03-04', '2015-03-04', 20),
('1000681', 'productmaster', 'RAC006', '0', '2015-03-04', '2015-03-04', 21),
('6351019', 'productmaster', 'RAC006', '2', '2015-03-04', '2015-04-06', 22),
('RAC9992', 'productmaster', 'RAC006', '2', '2015-03-04', '2015-03-05', 23),
('RAC9991', 'productmaster', 'RAC006', '2', '2015-03-04', '2015-03-04', 24),
('1000681', 'productmaster', 'RAC007', '0', '2015-03-04', '2015-03-04', 25),
('6351019', 'productmaster', 'RAC007', '2', '2015-03-04', '2015-04-06', 26),
('RAC9992', 'productmaster', 'RAC007', '2', '2015-03-04', '2015-03-05', 27),
('RAC9991', 'productmaster', 'RAC007', '2', '2015-03-04', '2015-03-04', 28),
('1000681', 'productmaster', 'RAC008', '0', '2015-03-04', '2015-03-04', 29),
('6351019', 'productmaster', 'RAC008', '2', '2015-03-04', '2015-04-06', 30),
('RAC9992', 'productmaster', 'RAC008', '2', '2015-03-04', '2015-03-05', 31),
('RAC9991', 'productmaster', 'RAC008', '2', '2015-03-04', '2015-03-04', 32),
('1000681', 'productmaster', 'RAC009', '0', '2015-03-04', '2015-03-04', 33),
('6351019', 'productmaster', 'RAC009', '2', '2015-03-04', '2015-04-06', 34),
('RAC9992', 'productmaster', 'RAC009', '2', '2015-03-04', '2015-03-05', 35),
('RAC9991', 'productmaster', 'RAC009', '2', '2015-03-04', '2015-03-04', 36),
('1000681', 'productmaster', 'RAC010', '0', '2015-03-04', '2015-03-04', 37),
('6351019', 'productmaster', 'RAC010', '2', '2015-03-04', '2015-04-06', 38),
('RAC9992', 'productmaster', 'RAC010', '2', '2015-03-04', '2015-03-05', 39),
('RAC9991', 'productmaster', 'RAC010', '2', '2015-03-04', '2015-03-04', 40),
('1000681', 'productmaster', 'RAC011', '0', '2015-03-04', '2015-03-04', 41),
('6351019', 'productmaster', 'RAC011', '2', '2015-03-04', '2015-04-06', 42),
('RAC9992', 'productmaster', 'RAC011', '2', '2015-03-04', '2015-03-05', 43),
('RAC9991', 'productmaster', 'RAC011', '2', '2015-03-04', '2015-03-04', 44),
('1000681', 'productmaster', 'RAC012', '0', '2015-03-04', '2015-03-04', 45),
('6351019', 'productmaster', 'RAC012', '2', '2015-03-04', '2015-04-06', 46),
('RAC9992', 'productmaster', 'RAC012', '2', '2015-03-04', '2015-03-05', 47),
('RAC9991', 'productmaster', 'RAC012', '2', '2015-03-04', '2015-03-04', 48),
('1000681', 'productmaster', 'RAC013', '0', '2015-03-04', '2015-03-04', 49),
('6351019', 'productmaster', 'RAC013', '2', '2015-03-04', '2015-04-06', 50),
('RAC9992', 'productmaster', 'RAC013', '2', '2015-03-04', '2015-03-05', 51),
('RAC9991', 'productmaster', 'RAC013', '2', '2015-03-04', '2015-03-04', 52),
('1000681', 'productmaster', 'RAC014', '0', '2015-03-04', '2015-03-04', 53),
('6351019', 'productmaster', 'RAC014', '2', '2015-03-04', '2015-04-06', 54),
('RAC9992', 'productmaster', 'RAC014', '2', '2015-03-04', '2015-03-05', 55),
('RAC9991', 'productmaster', 'RAC014', '2', '2015-03-04', '2015-03-04', 56),
('1000681', 'productmaster', 'RAC015', '0', '2015-03-04', '2015-03-04', 57),
('6351019', 'productmaster', 'RAC015', '2', '2015-03-04', '2015-04-06', 58),
('RAC9992', 'productmaster', 'RAC015', '2', '2015-03-04', '2015-03-05', 59),
('RAC9991', 'productmaster', 'RAC015', '2', '2015-03-04', '2015-03-04', 60),
('1000681', 'productmaster', 'RAC016', '0', '2015-03-04', '2015-03-04', 61),
('6351019', 'productmaster', 'RAC016', '2', '2015-03-04', '2015-04-06', 62),
('RAC9992', 'productmaster', 'RAC016', '2', '2015-03-04', '2015-03-05', 63),
('RAC9991', 'productmaster', 'RAC016', '2', '2015-03-04', '2015-03-04', 64),
('1000681', 'productmaster', 'PRODUCT30', '0', '2015-03-16', '2015-03-16', 65),
('1000681', 'productmaster', 'PRODUCT40', '0', '2015-03-16', '2015-03-16', 66),
('1000681', 'productmaster', 'RAC001', '0', '2015-03-16', '2015-03-16', 67),
('1000681', 'productmaster', 'RAC002', '0', '2015-03-16', '2015-03-16', 68),
('1000681', 'productmaster', 'RAC004', '0', '2015-03-16', '2015-03-16', 69),
('1000681', 'productmaster', 'RAC006', '0', '2015-03-16', '2015-03-16', 70),
('1000681', 'productmaster', 'RAC007', '0', '2015-03-16', '2015-03-16', 71),
('1000681', 'productmaster', 'RAC008', '0', '2015-03-16', '2015-03-16', 72),
('1000681', 'productmaster', 'RAC009', '0', '2015-03-16', '2015-03-16', 73),
('1000681', 'productmaster', 'RAC010', '0', '2015-03-16', '2015-03-16', 74),
('1000681', 'productmaster', 'RAC011', '0', '2015-03-16', '2015-03-16', 75),
('1000681', 'productmaster', 'RAC012', '0', '2015-03-16', '2015-03-16', 76),
('1000681', 'productmaster', 'RAC013', '0', '2015-03-16', '2015-03-16', 77),
('1000681', 'productmaster', 'RAC014', '0', '2015-03-16', '2015-03-16', 78),
('1000681', 'productmaster', 'RAC015', '0', '2015-03-16', '2015-03-16', 79),
('1000681', 'productmaster', 'RAC016', '0', '2015-03-16', '2015-03-16', 80),
('6351019', 'productmaster', 'PRODUCT30', '2', '2015-03-16', '2015-04-06', 81),
('6351019', 'productmaster', 'PRODUCT40', '2', '2015-03-16', '2015-04-06', 82),
('6351019', 'productmaster', 'RAC001', '2', '2015-03-16', '2015-04-06', 83),
('6351019', 'productmaster', 'RAC002', '2', '2015-03-16', '2015-04-06', 84),
('6351019', 'productmaster', 'RAC004', '2', '2015-03-16', '2015-04-06', 85),
('6351019', 'productmaster', 'RAC006', '2', '2015-03-16', '2015-04-06', 86),
('6351019', 'productmaster', 'RAC007', '2', '2015-03-16', '2015-04-06', 87),
('6351019', 'productmaster', 'RAC008', '2', '2015-03-16', '2015-04-06', 88),
('6351019', 'productmaster', 'RAC009', '2', '2015-03-16', '2015-04-06', 89),
('6351019', 'productmaster', 'RAC010', '2', '2015-03-16', '2015-04-06', 90),
('6351019', 'productmaster', 'RAC011', '2', '2015-03-16', '2015-04-06', 91),
('6351019', 'productmaster', 'RAC012', '2', '2015-03-16', '2015-04-06', 92),
('6351019', 'productmaster', 'RAC013', '2', '2015-03-16', '2015-04-06', 93),
('6351019', 'productmaster', 'RAC014', '2', '2015-03-16', '2015-04-06', 94),
('6351019', 'productmaster', 'RAC015', '2', '2015-03-16', '2015-04-06', 95),
('6351019', 'productmaster', 'RAC016', '2', '2015-03-16', '2015-04-06', 96),
('1432434', 'productmaster', 'PRODUCT30', '2', '2015-03-16', '2015-03-16', 97),
('1432434', 'productmaster', 'PRODUCT40', '2', '2015-03-16', '2015-03-16', 98),
('1432434', 'productmaster', 'RAC001', '2', '2015-03-16', '2015-03-16', 99),
('1432434', 'productmaster', 'RAC002', '2', '2015-03-16', '2015-03-16', 100),
('1432434', 'productmaster', 'RAC004', '2', '2015-03-16', '2015-03-16', 101),
('1432434', 'productmaster', 'RAC006', '2', '2015-03-16', '2015-03-16', 102),
('1432434', 'productmaster', 'RAC007', '2', '2015-03-16', '2015-03-16', 103),
('1432434', 'productmaster', 'RAC008', '2', '2015-03-16', '2015-03-16', 104),
('1432434', 'productmaster', 'RAC009', '2', '2015-03-16', '2015-03-16', 105),
('1432434', 'productmaster', 'RAC010', '2', '2015-03-16', '2015-03-16', 106),
('1432434', 'productmaster', 'RAC011', '2', '2015-03-16', '2015-03-16', 107),
('1432434', 'productmaster', 'RAC012', '2', '2015-03-16', '2015-03-16', 108),
('1432434', 'productmaster', 'RAC013', '2', '2015-03-16', '2015-03-16', 109),
('1432434', 'productmaster', 'RAC014', '2', '2015-03-16', '2015-03-16', 110),
('1432434', 'productmaster', 'RAC015', '2', '2015-03-16', '2015-03-16', 111),
('1432434', 'productmaster', 'RAC016', '2', '2015-03-16', '2015-03-16', 112);

-- --------------------------------------------------------

--
-- Stand-in structure for view `productmaster_view`
--
CREATE TABLE IF NOT EXISTS `productmaster_view` (
`ProductCode` varchar(50)
,`ProductDescription` varchar(100)
,`ProductType` varchar(50)
,`warrantyapplicable` varchar(5)
,`Status` varchar(50)
,`productuom` varchar(50)
,`Id` int(11)
);
-- --------------------------------------------------------

--
-- Table structure for table `productsegmentmaster`
--

CREATE TABLE IF NOT EXISTS `productsegmentmaster` (
  `ProductSegmentCode` varchar(50) NOT NULL DEFAULT '',
  `ProductSegment` varchar(50) DEFAULT NULL,
  `ProductGroup` varchar(50) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `m_date` datetime DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ProductSegmentCode`),
  UNIQUE KEY `ProductSegment` (`ProductSegment`),
  KEY `ID` (`id`),
  KEY `ID_2` (`id`),
  KEY `ID_3` (`id`),
  KEY `ID_4` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `productsegmentmaster`
--

INSERT INTO `productsegmentmaster` (`ProductSegmentCode`, `ProductSegment`, `ProductGroup`, `user_id`, `m_date`, `id`) VALUES
('PSC1', 'PS SAMPLE 1', 'PGC1', 'admin', '2014-09-18 11:05:57', 1),
('PSC2', 'PS SAMPLE 2', 'PGC2', 'admin', '2014-09-18 11:06:13', 2);

-- --------------------------------------------------------

--
-- Table structure for table `productsegmentupload`
--

CREATE TABLE IF NOT EXISTS `productsegmentupload` (
  `Franchiseecode` varchar(30) DEFAULT NULL,
  `Masters` varchar(50) DEFAULT NULL,
  `Code` varchar(100) DEFAULT NULL,
  `Status` varchar(30) DEFAULT NULL,
  `InsertDate` date DEFAULT NULL,
  `Deliverydae` date DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `productsegmentupload`
--

INSERT INTO `productsegmentupload` (`Franchiseecode`, `Masters`, `Code`, `Status`, `InsertDate`, `Deliverydae`, `id`) VALUES
('1000681', 'productsegmentmaster', 'PSC1', '0', '2015-02-10', '2015-02-10', 1),
('1000681', 'productsegmentmaster', 'PSC2', '0', '2015-02-10', '2015-02-10', 2),
('6351019', 'productsegmentmaster', 'PSC1', '2', '2015-02-10', '2015-04-06', 3),
('6351019', 'productsegmentmaster', 'PSC2', '2', '2015-02-10', '2015-04-06', 4),
('RAC9992', 'productsegmentmaster', 'PSC1', '2', '2015-03-04', '2015-03-05', 5),
('RAC9992', 'productsegmentmaster', 'PSC2', '2', '2015-03-04', '2015-03-05', 6),
('RAC9991', 'productsegmentmaster', 'PSC1', '2', '2015-03-04', '2015-03-04', 7),
('RAC9991', 'productsegmentmaster', 'PSC2', '2', '2015-03-04', '2015-03-04', 8),
('1000681', 'productsegmentmaster', 'PSC1', '0', '2015-03-16', '2015-03-16', 9),
('1000681', 'productsegmentmaster', 'PSC2', '0', '2015-03-16', '2015-03-16', 10),
('6351019', 'productsegmentmaster', 'PSC1', '2', '2015-03-16', '2015-04-06', 11),
('6351019', 'productsegmentmaster', 'PSC2', '2', '2015-03-16', '2015-04-06', 12),
('1432434', 'productsegmentmaster', 'PSC1', '2', '2015-03-16', '2015-03-16', 13),
('1432434', 'productsegmentmaster', 'PSC2', '2', '2015-03-16', '2015-03-16', 14);

-- --------------------------------------------------------

--
-- Table structure for table `productserviceupload`
--

CREATE TABLE IF NOT EXISTS `productserviceupload` (
  `Franchiseecode` varchar(30) DEFAULT NULL,
  `Masters` varchar(50) DEFAULT NULL,
  `Code` varchar(100) DEFAULT NULL,
  `Status` varchar(30) DEFAULT NULL,
  `InsertDate` date DEFAULT NULL,
  `Deliverydae` date DEFAULT NULL,
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `producttypemaster`
--

CREATE TABLE IF NOT EXISTS `producttypemaster` (
  `ProductTypeCode` varchar(15) NOT NULL DEFAULT '',
  `ProductTypeName` varchar(50) DEFAULT NULL,
  `ProductSegment` varchar(50) DEFAULT NULL,
  `ProductGroup` varchar(50) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `m_date` datetime DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ProductTypeCode`),
  UNIQUE KEY `ProductTypeName` (`ProductTypeName`),
  KEY `ID` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `producttypemaster`
--

INSERT INTO `producttypemaster` (`ProductTypeCode`, `ProductTypeName`, `ProductSegment`, `ProductGroup`, `user_id`, `m_date`, `id`) VALUES
('AFG', 'ACIDIC FRUITED HYBRIDS', 'PSC1', 'PGC1', 'admin', '2014-11-26 14:58:39', 27),
('ATH', 'ASIATIC TYPE HYBRIDS', 'PSC1', 'PGC1', 'admin', '2014-11-26 14:58:32', 3),
('BAH', 'BEIT ALPHA HYBRIDS', 'PSC1', 'PGC1', 'admin', '2014-11-26 14:58:36', 18),
('CB', 'CLUSTER BEANS', 'PSC1', 'PGC1', 'admin', '2014-11-26 14:58:37', 19),
('CT', 'CANTALOUPE TYPE', 'PSC1', 'PGC1', 'admin', '2014-11-26 14:58:38', 21),
('CV', 'CARROT VARITIES', 'PSC1', 'PGC1', 'admin', '2014-11-26 14:58:35', 13),
('DG', 'DARK GREEN', 'PSC1', 'PGC1', 'admin', '2014-11-26 14:58:39', 25),
('DPH', 'DUAL PURPOSE HYBRIDS', 'PSC1', 'PGC1', 'admin', '2014-11-26 14:58:36', 15),
('EST', 'EARLY SEASON TYPE', 'PSC1', 'PGC1', 'admin', '2014-11-26 14:58:35', 14),
('FHH', 'FLAT HEAD HYBRIDS', 'PSC1', 'PGC1', 'admin', '2014-11-26 14:58:35', 10),
('GHC', 'GREEN HOUSE CULTIVATION', 'PSC1', 'PGC1', 'admin', '2014-11-26 14:58:35', 12),
('GR', 'GREEN ROUND', 'PSC1', 'PGC1', 'admin', '2014-11-26 14:58:34', 8),
('HBT', 'HB', 'PSC1', 'PGC1', 'admin', '2014-11-26 14:58:36', 16),
('HHT', 'HH', 'PSC1', 'PGC1', 'admin', '2014-11-26 14:58:36', 17),
('IBT', 'ICE BOX TYPE HYBRIDS', 'PSC1', 'PGC1', 'admin', '2014-11-26 14:58:39', 28),
('LC', 'LONG CYLINDRICAL', 'PSC1', 'PGC1', 'admin', '2014-11-26 14:58:33', 6),
('LG', 'LIGHT GREEN', 'PSC1', 'PGC1', 'admin', '2014-11-26 14:58:39', 26),
('LSH', 'LONG SPINDLE HYBRIDS', 'PSC1', 'PGC1', 'admin', '2014-11-26 14:58:33', 5),
('MLC', 'MEDIUM LONG CYLINDRICAL', 'PSC1', 'PGC1', 'admin', '2014-11-26 14:58:34', 7),
('MV', 'MAIZE VARIETIES', 'PSC1', 'PGC1', 'admin', '2014-11-26 14:58:37', 20),
('OH', 'OKRA HYBRIDS', 'PSC1', 'PGC1', 'admin', '2014-11-26 14:58:38', 22),
('PEV', 'PEAS VARIETIES', 'PSC1', 'PGC1', 'admin', '2014-11-26 14:58:39', 24),
('PL', 'PURPLE LONG', 'PSC1', 'PGC1', 'admin', '2014-11-26 14:58:34', 9),
('PTC1', 'PT SAMPLE 1', 'PSC1', NULL, 'admin', '2015-02-10 15:27:20', 1),
('PTC2', 'PT SAMPLE 2', 'PSC2', NULL, 'admin', '2014-09-18 11:07:14', 2),
('PV', 'PADDY VARITIES', 'PSC1', 'PGC1', 'admin', '2014-11-26 14:58:38', 23),
('RHH', 'ROUND HEAD HYBRIDS', 'PSC1', 'PGC1', 'admin', '2014-11-26 14:58:35', 11),
('SPH', 'SHORT SPINDLE HYBRIDS', 'PSC1', 'PGC1', 'admin', '2014-11-26 14:58:33', 4);

-- --------------------------------------------------------

--
-- Table structure for table `producttypeupload`
--

CREATE TABLE IF NOT EXISTS `producttypeupload` (
  `Franchiseecode` varchar(30) DEFAULT NULL,
  `Masters` varchar(50) DEFAULT NULL,
  `Code` varchar(100) DEFAULT NULL,
  `Status` varchar(30) DEFAULT NULL,
  `InsertDate` date DEFAULT NULL,
  `Deliverydae` date DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=197 ;

--
-- Dumping data for table `producttypeupload`
--

INSERT INTO `producttypeupload` (`Franchiseecode`, `Masters`, `Code`, `Status`, `InsertDate`, `Deliverydae`, `id`) VALUES
('1000681', 'producttypemaster', 'PTC1', '0', '2015-02-10', '2015-02-10', 1),
('1000681', 'producttypemaster', 'PTC2', '0', '2015-02-10', '2015-02-10', 2),
('1000681', 'producttypemaster', 'ATH', '0', '2015-02-10', '2015-02-10', 3),
('1000681', 'producttypemaster', 'SPH', '0', '2015-02-10', '2015-02-10', 4),
('1000681', 'producttypemaster', 'LSH', '0', '2015-02-10', '2015-02-10', 5),
('1000681', 'producttypemaster', 'LC', '0', '2015-02-10', '2015-02-10', 6),
('1000681', 'producttypemaster', 'MLC', '0', '2015-02-10', '2015-02-10', 7),
('1000681', 'producttypemaster', 'GR', '0', '2015-02-10', '2015-02-10', 8),
('1000681', 'producttypemaster', 'PL', '0', '2015-02-10', '2015-02-10', 9),
('1000681', 'producttypemaster', 'FHH', '0', '2015-02-10', '2015-02-10', 10),
('1000681', 'producttypemaster', 'RHH', '0', '2015-02-10', '2015-02-10', 11),
('1000681', 'producttypemaster', 'GHC', '0', '2015-02-10', '2015-02-10', 12),
('1000681', 'producttypemaster', 'CV', '0', '2015-02-10', '2015-02-10', 13),
('1000681', 'producttypemaster', 'EST', '0', '2015-02-10', '2015-02-10', 14),
('1000681', 'producttypemaster', 'DPH', '0', '2015-02-10', '2015-02-10', 15),
('1000681', 'producttypemaster', 'HBT', '0', '2015-02-10', '2015-02-10', 16),
('1000681', 'producttypemaster', 'HHT', '0', '2015-02-10', '2015-02-10', 17),
('1000681', 'producttypemaster', 'BAH', '0', '2015-02-10', '2015-02-10', 18),
('1000681', 'producttypemaster', 'CB', '0', '2015-02-10', '2015-02-10', 19),
('1000681', 'producttypemaster', 'MV', '0', '2015-02-10', '2015-02-10', 20),
('1000681', 'producttypemaster', 'CT', '0', '2015-02-10', '2015-02-10', 21),
('1000681', 'producttypemaster', 'OH', '0', '2015-02-10', '2015-02-10', 22),
('1000681', 'producttypemaster', 'PV', '0', '2015-02-10', '2015-02-10', 23),
('1000681', 'producttypemaster', 'PEV', '0', '2015-02-10', '2015-02-10', 24),
('1000681', 'producttypemaster', 'DG', '0', '2015-02-10', '2015-02-10', 25),
('1000681', 'producttypemaster', 'LG', '0', '2015-02-10', '2015-02-10', 26),
('1000681', 'producttypemaster', 'AFG', '0', '2015-02-10', '2015-02-10', 27),
('1000681', 'producttypemaster', 'IBT', '0', '2015-02-10', '2015-02-10', 28),
('6351019', 'producttypemaster', 'PTC1', '2', '2015-02-10', '2015-04-06', 29),
('6351019', 'producttypemaster', 'PTC2', '2', '2015-02-10', '2015-04-06', 30),
('6351019', 'producttypemaster', 'ATH', '2', '2015-02-10', '2015-04-06', 31),
('6351019', 'producttypemaster', 'SPH', '2', '2015-02-10', '2015-04-06', 32),
('6351019', 'producttypemaster', 'LSH', '2', '2015-02-10', '2015-04-06', 33),
('6351019', 'producttypemaster', 'LC', '2', '2015-02-10', '2015-04-06', 34),
('6351019', 'producttypemaster', 'MLC', '2', '2015-02-10', '2015-04-06', 35),
('6351019', 'producttypemaster', 'GR', '2', '2015-02-10', '2015-04-06', 36),
('6351019', 'producttypemaster', 'PL', '2', '2015-02-10', '2015-04-06', 37),
('6351019', 'producttypemaster', 'FHH', '2', '2015-02-10', '2015-04-06', 38),
('6351019', 'producttypemaster', 'RHH', '2', '2015-02-10', '2015-04-06', 39),
('6351019', 'producttypemaster', 'GHC', '2', '2015-02-10', '2015-04-06', 40),
('6351019', 'producttypemaster', 'CV', '2', '2015-02-10', '2015-04-06', 41),
('6351019', 'producttypemaster', 'EST', '2', '2015-02-10', '2015-04-06', 42),
('6351019', 'producttypemaster', 'DPH', '2', '2015-02-10', '2015-04-06', 43),
('6351019', 'producttypemaster', 'HBT', '2', '2015-02-10', '2015-04-06', 44),
('6351019', 'producttypemaster', 'HHT', '2', '2015-02-10', '2015-04-06', 45),
('6351019', 'producttypemaster', 'BAH', '2', '2015-02-10', '2015-04-06', 46),
('6351019', 'producttypemaster', 'CB', '2', '2015-02-10', '2015-04-06', 47),
('6351019', 'producttypemaster', 'MV', '2', '2015-02-10', '2015-04-06', 48),
('6351019', 'producttypemaster', 'CT', '2', '2015-02-10', '2015-04-06', 49),
('6351019', 'producttypemaster', 'OH', '2', '2015-02-10', '2015-04-06', 50),
('6351019', 'producttypemaster', 'PV', '2', '2015-02-10', '2015-04-06', 51),
('6351019', 'producttypemaster', 'PEV', '2', '2015-02-10', '2015-04-06', 52),
('6351019', 'producttypemaster', 'DG', '2', '2015-02-10', '2015-04-06', 53),
('6351019', 'producttypemaster', 'LG', '2', '2015-02-10', '2015-04-06', 54),
('6351019', 'producttypemaster', 'AFG', '2', '2015-02-10', '2015-04-06', 55),
('6351019', 'producttypemaster', 'IBT', '2', '2015-02-10', '2015-04-06', 56),
('RAC9992', 'producttypemaster', 'PTC1', '2', '2015-03-04', '2015-03-05', 57),
('RAC9992', 'producttypemaster', 'PTC2', '2', '2015-03-04', '2015-03-05', 58),
('RAC9992', 'producttypemaster', 'ATH', '2', '2015-03-04', '2015-03-05', 59),
('RAC9992', 'producttypemaster', 'SPH', '2', '2015-03-04', '2015-03-05', 60),
('RAC9992', 'producttypemaster', 'LSH', '2', '2015-03-04', '2015-03-05', 61),
('RAC9992', 'producttypemaster', 'LC', '2', '2015-03-04', '2015-03-05', 62),
('RAC9992', 'producttypemaster', 'MLC', '2', '2015-03-04', '2015-03-05', 63),
('RAC9992', 'producttypemaster', 'GR', '2', '2015-03-04', '2015-03-05', 64),
('RAC9992', 'producttypemaster', 'PL', '2', '2015-03-04', '2015-03-05', 65),
('RAC9992', 'producttypemaster', 'FHH', '2', '2015-03-04', '2015-03-05', 66),
('RAC9992', 'producttypemaster', 'RHH', '2', '2015-03-04', '2015-03-05', 67),
('RAC9992', 'producttypemaster', 'GHC', '2', '2015-03-04', '2015-03-05', 68),
('RAC9992', 'producttypemaster', 'CV', '2', '2015-03-04', '2015-03-05', 69),
('RAC9992', 'producttypemaster', 'EST', '2', '2015-03-04', '2015-03-05', 70),
('RAC9992', 'producttypemaster', 'DPH', '2', '2015-03-04', '2015-03-05', 71),
('RAC9992', 'producttypemaster', 'HBT', '2', '2015-03-04', '2015-03-05', 72),
('RAC9992', 'producttypemaster', 'HHT', '2', '2015-03-04', '2015-03-05', 73),
('RAC9992', 'producttypemaster', 'BAH', '2', '2015-03-04', '2015-03-05', 74),
('RAC9992', 'producttypemaster', 'CB', '2', '2015-03-04', '2015-03-05', 75),
('RAC9992', 'producttypemaster', 'MV', '2', '2015-03-04', '2015-03-05', 76),
('RAC9992', 'producttypemaster', 'CT', '2', '2015-03-04', '2015-03-05', 77),
('RAC9992', 'producttypemaster', 'OH', '2', '2015-03-04', '2015-03-05', 78),
('RAC9992', 'producttypemaster', 'PV', '2', '2015-03-04', '2015-03-05', 79),
('RAC9992', 'producttypemaster', 'PEV', '2', '2015-03-04', '2015-03-05', 80),
('RAC9992', 'producttypemaster', 'DG', '2', '2015-03-04', '2015-03-05', 81),
('RAC9992', 'producttypemaster', 'LG', '2', '2015-03-04', '2015-03-05', 82),
('RAC9992', 'producttypemaster', 'AFG', '2', '2015-03-04', '2015-03-05', 83),
('RAC9992', 'producttypemaster', 'IBT', '2', '2015-03-04', '2015-03-05', 84),
('RAC9991', 'producttypemaster', 'PTC1', '2', '2015-03-04', '2015-03-04', 85),
('RAC9991', 'producttypemaster', 'PTC2', '2', '2015-03-04', '2015-03-04', 86),
('RAC9991', 'producttypemaster', 'ATH', '2', '2015-03-04', '2015-03-04', 87),
('RAC9991', 'producttypemaster', 'SPH', '2', '2015-03-04', '2015-03-04', 88),
('RAC9991', 'producttypemaster', 'LSH', '2', '2015-03-04', '2015-03-04', 89),
('RAC9991', 'producttypemaster', 'LC', '2', '2015-03-04', '2015-03-04', 90),
('RAC9991', 'producttypemaster', 'MLC', '2', '2015-03-04', '2015-03-04', 91),
('RAC9991', 'producttypemaster', 'GR', '2', '2015-03-04', '2015-03-04', 92),
('RAC9991', 'producttypemaster', 'PL', '2', '2015-03-04', '2015-03-04', 93),
('RAC9991', 'producttypemaster', 'FHH', '2', '2015-03-04', '2015-03-04', 94),
('RAC9991', 'producttypemaster', 'RHH', '2', '2015-03-04', '2015-03-04', 95),
('RAC9991', 'producttypemaster', 'GHC', '2', '2015-03-04', '2015-03-04', 96),
('RAC9991', 'producttypemaster', 'CV', '2', '2015-03-04', '2015-03-04', 97),
('RAC9991', 'producttypemaster', 'EST', '2', '2015-03-04', '2015-03-04', 98),
('RAC9991', 'producttypemaster', 'DPH', '2', '2015-03-04', '2015-03-04', 99),
('RAC9991', 'producttypemaster', 'HBT', '2', '2015-03-04', '2015-03-04', 100),
('RAC9991', 'producttypemaster', 'HHT', '2', '2015-03-04', '2015-03-04', 101),
('RAC9991', 'producttypemaster', 'BAH', '2', '2015-03-04', '2015-03-04', 102),
('RAC9991', 'producttypemaster', 'CB', '2', '2015-03-04', '2015-03-04', 103),
('RAC9991', 'producttypemaster', 'MV', '2', '2015-03-04', '2015-03-04', 104),
('RAC9991', 'producttypemaster', 'CT', '2', '2015-03-04', '2015-03-04', 105),
('RAC9991', 'producttypemaster', 'OH', '2', '2015-03-04', '2015-03-04', 106),
('RAC9991', 'producttypemaster', 'PV', '2', '2015-03-04', '2015-03-04', 107),
('RAC9991', 'producttypemaster', 'PEV', '2', '2015-03-04', '2015-03-04', 108),
('RAC9991', 'producttypemaster', 'DG', '2', '2015-03-04', '2015-03-04', 109),
('RAC9991', 'producttypemaster', 'LG', '2', '2015-03-04', '2015-03-04', 110),
('RAC9991', 'producttypemaster', 'AFG', '2', '2015-03-04', '2015-03-04', 111),
('RAC9991', 'producttypemaster', 'IBT', '2', '2015-03-04', '2015-03-04', 112),
('1000681', 'producttypemaster', 'PTC1', '0', '2015-03-16', '2015-03-16', 113),
('1000681', 'producttypemaster', 'PTC2', '0', '2015-03-16', '2015-03-16', 114),
('1000681', 'producttypemaster', 'ATH', '0', '2015-03-16', '2015-03-16', 115),
('1000681', 'producttypemaster', 'SPH', '0', '2015-03-16', '2015-03-16', 116),
('1000681', 'producttypemaster', 'LSH', '0', '2015-03-16', '2015-03-16', 117),
('1000681', 'producttypemaster', 'LC', '0', '2015-03-16', '2015-03-16', 118),
('1000681', 'producttypemaster', 'MLC', '0', '2015-03-16', '2015-03-16', 119),
('1000681', 'producttypemaster', 'GR', '0', '2015-03-16', '2015-03-16', 120),
('1000681', 'producttypemaster', 'PL', '0', '2015-03-16', '2015-03-16', 121),
('1000681', 'producttypemaster', 'FHH', '0', '2015-03-16', '2015-03-16', 122),
('1000681', 'producttypemaster', 'RHH', '0', '2015-03-16', '2015-03-16', 123),
('1000681', 'producttypemaster', 'GHC', '0', '2015-03-16', '2015-03-16', 124),
('1000681', 'producttypemaster', 'CV', '0', '2015-03-16', '2015-03-16', 125),
('1000681', 'producttypemaster', 'EST', '0', '2015-03-16', '2015-03-16', 126),
('1000681', 'producttypemaster', 'DPH', '0', '2015-03-16', '2015-03-16', 127),
('1000681', 'producttypemaster', 'HBT', '0', '2015-03-16', '2015-03-16', 128),
('1000681', 'producttypemaster', 'HHT', '0', '2015-03-16', '2015-03-16', 129),
('1000681', 'producttypemaster', 'BAH', '0', '2015-03-16', '2015-03-16', 130),
('1000681', 'producttypemaster', 'CB', '0', '2015-03-16', '2015-03-16', 131),
('1000681', 'producttypemaster', 'MV', '0', '2015-03-16', '2015-03-16', 132),
('1000681', 'producttypemaster', 'CT', '0', '2015-03-16', '2015-03-16', 133),
('1000681', 'producttypemaster', 'OH', '0', '2015-03-16', '2015-03-16', 134),
('1000681', 'producttypemaster', 'PV', '0', '2015-03-16', '2015-03-16', 135),
('1000681', 'producttypemaster', 'PEV', '0', '2015-03-16', '2015-03-16', 136),
('1000681', 'producttypemaster', 'DG', '0', '2015-03-16', '2015-03-16', 137),
('1000681', 'producttypemaster', 'LG', '0', '2015-03-16', '2015-03-16', 138),
('1000681', 'producttypemaster', 'AFG', '0', '2015-03-16', '2015-03-16', 139),
('1000681', 'producttypemaster', 'IBT', '0', '2015-03-16', '2015-03-16', 140),
('6351019', 'producttypemaster', 'PTC1', '2', '2015-03-16', '2015-04-06', 141),
('6351019', 'producttypemaster', 'PTC2', '2', '2015-03-16', '2015-04-06', 142),
('6351019', 'producttypemaster', 'ATH', '2', '2015-03-16', '2015-04-06', 143),
('6351019', 'producttypemaster', 'SPH', '2', '2015-03-16', '2015-04-06', 144),
('6351019', 'producttypemaster', 'LSH', '2', '2015-03-16', '2015-04-06', 145),
('6351019', 'producttypemaster', 'LC', '2', '2015-03-16', '2015-04-06', 146),
('6351019', 'producttypemaster', 'MLC', '2', '2015-03-16', '2015-04-06', 147),
('6351019', 'producttypemaster', 'GR', '2', '2015-03-16', '2015-04-06', 148),
('6351019', 'producttypemaster', 'PL', '2', '2015-03-16', '2015-04-06', 149),
('6351019', 'producttypemaster', 'FHH', '2', '2015-03-16', '2015-04-06', 150),
('6351019', 'producttypemaster', 'RHH', '2', '2015-03-16', '2015-04-06', 151),
('6351019', 'producttypemaster', 'GHC', '2', '2015-03-16', '2015-04-06', 152),
('6351019', 'producttypemaster', 'CV', '2', '2015-03-16', '2015-04-06', 153),
('6351019', 'producttypemaster', 'EST', '2', '2015-03-16', '2015-04-06', 154),
('6351019', 'producttypemaster', 'DPH', '2', '2015-03-16', '2015-04-06', 155),
('6351019', 'producttypemaster', 'HBT', '2', '2015-03-16', '2015-04-06', 156),
('6351019', 'producttypemaster', 'HHT', '2', '2015-03-16', '2015-04-06', 157),
('6351019', 'producttypemaster', 'BAH', '2', '2015-03-16', '2015-04-06', 158),
('6351019', 'producttypemaster', 'CB', '2', '2015-03-16', '2015-04-06', 159),
('6351019', 'producttypemaster', 'MV', '2', '2015-03-16', '2015-04-06', 160),
('6351019', 'producttypemaster', 'CT', '2', '2015-03-16', '2015-04-06', 161),
('6351019', 'producttypemaster', 'OH', '2', '2015-03-16', '2015-04-06', 162),
('6351019', 'producttypemaster', 'PV', '2', '2015-03-16', '2015-04-06', 163),
('6351019', 'producttypemaster', 'PEV', '2', '2015-03-16', '2015-04-06', 164),
('6351019', 'producttypemaster', 'DG', '2', '2015-03-16', '2015-04-06', 165),
('6351019', 'producttypemaster', 'LG', '2', '2015-03-16', '2015-04-06', 166),
('6351019', 'producttypemaster', 'AFG', '2', '2015-03-16', '2015-04-06', 167),
('6351019', 'producttypemaster', 'IBT', '2', '2015-03-16', '2015-04-06', 168),
('1432434', 'producttypemaster', 'PTC1', '2', '2015-03-16', '2015-03-16', 169),
('1432434', 'producttypemaster', 'PTC2', '2', '2015-03-16', '2015-03-16', 170),
('1432434', 'producttypemaster', 'ATH', '2', '2015-03-16', '2015-03-16', 171),
('1432434', 'producttypemaster', 'SPH', '2', '2015-03-16', '2015-03-16', 172),
('1432434', 'producttypemaster', 'LSH', '2', '2015-03-16', '2015-03-16', 173),
('1432434', 'producttypemaster', 'LC', '2', '2015-03-16', '2015-03-16', 174),
('1432434', 'producttypemaster', 'MLC', '2', '2015-03-16', '2015-03-16', 175),
('1432434', 'producttypemaster', 'GR', '2', '2015-03-16', '2015-03-16', 176),
('1432434', 'producttypemaster', 'PL', '2', '2015-03-16', '2015-03-16', 177),
('1432434', 'producttypemaster', 'FHH', '2', '2015-03-16', '2015-03-16', 178),
('1432434', 'producttypemaster', 'RHH', '2', '2015-03-16', '2015-03-16', 179),
('1432434', 'producttypemaster', 'GHC', '2', '2015-03-16', '2015-03-16', 180),
('1432434', 'producttypemaster', 'CV', '2', '2015-03-16', '2015-03-16', 181),
('1432434', 'producttypemaster', 'EST', '2', '2015-03-16', '2015-03-16', 182),
('1432434', 'producttypemaster', 'DPH', '2', '2015-03-16', '2015-03-16', 183),
('1432434', 'producttypemaster', 'HBT', '2', '2015-03-16', '2015-03-16', 184),
('1432434', 'producttypemaster', 'HHT', '2', '2015-03-16', '2015-03-16', 185),
('1432434', 'producttypemaster', 'BAH', '2', '2015-03-16', '2015-03-16', 186),
('1432434', 'producttypemaster', 'CB', '2', '2015-03-16', '2015-03-16', 187),
('1432434', 'producttypemaster', 'MV', '2', '2015-03-16', '2015-03-16', 188),
('1432434', 'producttypemaster', 'CT', '2', '2015-03-16', '2015-03-16', 189),
('1432434', 'producttypemaster', 'OH', '2', '2015-03-16', '2015-03-16', 190),
('1432434', 'producttypemaster', 'PV', '2', '2015-03-16', '2015-03-16', 191),
('1432434', 'producttypemaster', 'PEV', '2', '2015-03-16', '2015-03-16', 192),
('1432434', 'producttypemaster', 'DG', '2', '2015-03-16', '2015-03-16', 193),
('1432434', 'producttypemaster', 'LG', '2', '2015-03-16', '2015-03-16', 194),
('1432434', 'producttypemaster', 'AFG', '2', '2015-03-16', '2015-03-16', 195),
('1432434', 'producttypemaster', 'IBT', '2', '2015-03-16', '2015-03-16', 196);

-- --------------------------------------------------------

--
-- Table structure for table `productuom`
--

CREATE TABLE IF NOT EXISTS `productuom` (
  `productuomcode` varchar(50) NOT NULL DEFAULT '',
  `productuom` varchar(50) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `m_date` datetime DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`productuomcode`),
  KEY `Id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `productuom`
--

INSERT INTO `productuom` (`productuomcode`, `productuom`, `user_id`, `m_date`, `id`) VALUES
('KG', 'KILOGRAM', 'admin', '2014-09-18 11:09:01', 2),
('NOS', 'NUMBERS', 'admin', '2014-09-18 11:08:33', 1);

-- --------------------------------------------------------

--
-- Table structure for table `productuomupload`
--

CREATE TABLE IF NOT EXISTS `productuomupload` (
  `Franchiseecode` varchar(30) DEFAULT NULL,
  `Masters` varchar(50) DEFAULT NULL,
  `Code` varchar(100) DEFAULT NULL,
  `Status` varchar(30) DEFAULT NULL,
  `InsertDate` date DEFAULT NULL,
  `Deliverydae` date DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `productuomupload`
--

INSERT INTO `productuomupload` (`Franchiseecode`, `Masters`, `Code`, `Status`, `InsertDate`, `Deliverydae`, `id`) VALUES
('1000681', 'productuom', 'NOS', '0', '2015-02-10', '2015-02-10', 1),
('1000681', 'productuom', 'KG', '0', '2015-02-10', '2015-02-10', 2),
('6351019', 'productuom', 'NOS', '2', '2015-02-10', '2015-04-06', 3),
('6351019', 'productuom', 'KG', '2', '2015-02-10', '2015-04-06', 4),
('RAC9992', 'productuom', 'NOS', '2', '2015-03-04', '2015-03-05', 5),
('RAC9992', 'productuom', 'KG', '2', '2015-03-04', '2015-03-05', 6),
('RAC9991', 'productuom', 'NOS', '2', '2015-03-04', '2015-03-04', 7),
('RAC9991', 'productuom', 'KG', '2', '2015-03-04', '2015-03-04', 8),
('1000681', 'productuom', 'NOS', '0', '2015-03-16', '2015-03-16', 9),
('1000681', 'productuom', 'KG', '0', '2015-03-16', '2015-03-16', 10),
('6351019', 'productuom', 'NOS', '2', '2015-03-16', '2015-04-06', 11),
('6351019', 'productuom', 'KG', '2', '2015-03-16', '2015-04-06', 12),
('1432434', 'productuom', 'NOS', '2', '2015-03-16', '2015-03-16', 13),
('1432434', 'productuom', 'KG', '2', '2015-03-16', '2015-03-16', 14);

-- --------------------------------------------------------

--
-- Table structure for table `productvehiclemakeupload`
--

CREATE TABLE IF NOT EXISTS `productvehiclemakeupload` (
  `Franchiseecode` varchar(30) DEFAULT NULL,
  `Masters` varchar(50) DEFAULT NULL,
  `Code` varchar(100) DEFAULT NULL,
  `Status` varchar(30) DEFAULT NULL,
  `InsertDate` date DEFAULT NULL,
  `Deliverydae` date DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `productvehiclemodelupload`
--

CREATE TABLE IF NOT EXISTS `productvehiclemodelupload` (
  `Franchiseecode` varchar(30) DEFAULT NULL,
  `Masters` varchar(50) DEFAULT NULL,
  `Code` varchar(100) DEFAULT NULL,
  `Status` varchar(30) DEFAULT NULL,
  `InsertDate` date DEFAULT NULL,
  `Deliverydae` date DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `productvehiclesegmentupload`
--

CREATE TABLE IF NOT EXISTS `productvehiclesegmentupload` (
  `Franchiseecode` varchar(30) DEFAULT NULL,
  `Masters` varchar(50) DEFAULT NULL,
  `Code` varchar(100) DEFAULT NULL,
  `Status` varchar(30) DEFAULT NULL,
  `InsertDate` date DEFAULT NULL,
  `Deliverydae` date DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `productwarranty`
--

CREATE TABLE IF NOT EXISTS `productwarranty` (
  `Category` varchar(100) DEFAULT NULL,
  `ProductCode` varchar(50) DEFAULT NULL,
  `FOC` int(20) DEFAULT NULL,
  `ProRataPeriod` int(20) DEFAULT NULL,
  `ManufactureDate` date DEFAULT NULL,
  `ManufactureWarranty` int(20) DEFAULT NULL,
  `ApplicableFormDate` date DEFAULT NULL,
  `Saleswarranty` int(20) DEFAULT NULL,
  `Kmrun` varchar(20) DEFAULT NULL,
  `oemname` varchar(50) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `m_date` datetime DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  KEY `ID` (`id`),
  KEY `ID_2` (`id`),
  KEY `index_2` (`Category`,`ProductCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `productwarrantyupload`
--

CREATE TABLE IF NOT EXISTS `productwarrantyupload` (
  `Franchiseecode` varchar(30) DEFAULT NULL,
  `Masters` varchar(50) DEFAULT NULL,
  `Code` varchar(100) DEFAULT NULL,
  `ManufactureDate` date DEFAULT NULL,
  `Status` varchar(30) DEFAULT NULL,
  `InsertDate` date DEFAULT NULL,
  `Deliverydae` date DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `productwarranty_view`
--
CREATE TABLE IF NOT EXISTS `productwarranty_view` (
`Category` varchar(100)
,`ProductCode` varchar(50)
,`ProductDescription` varchar(100)
,`FOC` int(20)
,`ProRataPeriod` int(20)
,`ManufactureDate` date
,`ManufactureWarranty` int(20)
,`ApplicableFormDate` date
,`Saleswarranty` int(20)
,`Kmrun` varchar(20)
,`oemname` varchar(50)
,`ID` int(11)
,`Status` varchar(30)
,`Franchiseecode` varchar(30)
);
-- --------------------------------------------------------

--
-- Table structure for table `proratalogic`
--

CREATE TABLE IF NOT EXISTS `proratalogic` (
  `category` varchar(50) DEFAULT NULL,
  `logiccode` varchar(50) DEFAULT NULL,
  `effectivedate` date DEFAULT NULL,
  `min` int(15) DEFAULT NULL,
  `max` int(15) DEFAULT NULL,
  `discount` int(15) DEFAULT NULL,
  `id` int(15) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `proratalogic_view`
--
CREATE TABLE IF NOT EXISTS `proratalogic_view` (
`category` varchar(50)
,`logiccode` varchar(50)
,`effectivedate` date
,`minimum` bigint(15)
,`min` bigint(15)
,`max` bigint(15)
,`discount` bigint(15)
);
-- --------------------------------------------------------

--
-- Table structure for table `proratamaterial`
--

CREATE TABLE IF NOT EXISTS `proratamaterial` (
  `pmno` varchar(50) NOT NULL,
  `pmdatedate` date NOT NULL,
  `dcwno` varchar(50) NOT NULL,
  `SalesType` varchar(50) NOT NULL,
  `partyledger` varchar(30) NOT NULL,
  `Decision` varchar(50) NOT NULL,
  `VoucherType` varchar(30) NOT NULL,
  `franchisecode` varchar(50) NOT NULL,
  `voucherstatus` varchar(30) NOT NULL,
  `dcwno_masterid` varchar(45) NOT NULL,
  `masterid` varchar(45) NOT NULL,
  PRIMARY KEY (`masterid`),
  UNIQUE KEY `masterid` (`masterid`),
  KEY `pmno` (`pmno`),
  KEY `index_4` (`pmdatedate`,`dcwno`,`franchisecode`,`voucherstatus`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `proratamaterialledger`
--

CREATE TABLE IF NOT EXISTS `proratamaterialledger` (
  `pmno` varchar(50) DEFAULT NULL,
  `pmdatedate` date DEFAULT NULL,
  `Taxledger` varchar(50) DEFAULT NULL,
  `Taxamount` double DEFAULT NULL,
  `franchisecode` varchar(20) DEFAULT NULL,
  `voucherstatus` varchar(30) DEFAULT NULL,
  `masterid` varchar(45) NOT NULL,
  KEY `index_1` (`masterid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `proratamaterial_details`
--

CREATE TABLE IF NOT EXISTS `proratamaterial_details` (
  `ProductCode` varchar(50) DEFAULT NULL,
  `ProductDescription` varchar(50) DEFAULT NULL,
  `Quantity` int(30) DEFAULT NULL,
  `Rate` double DEFAULT NULL,
  `Amount` float DEFAULT NULL,
  `taxvalue` double NOT NULL DEFAULT '0',
  `pmno` varchar(50) DEFAULT NULL,
  `pmdatedate` date DEFAULT NULL,
  `FranchiseCode` varchar(50) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `voucherstatus` varchar(30) DEFAULT NULL,
  `masterid` varchar(45) NOT NULL,
  KEY `index_1` (`ProductCode`,`masterid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Triggers `proratamaterial_details`
--
DROP TRIGGER IF EXISTS `after_pmpurchase_insert`;
DELIMITER //
CREATE TRIGGER `after_pmpurchase_insert` AFTER INSERT ON `proratamaterial_details`
 FOR EACH ROW BEGIN
		DECLARE Region_Name VARCHAR(50);
		DECLARE branch_name VARCHAR(50);
		DECLARE Franchise_name VARCHAR(50);
		DECLARE p_description VARCHAR(100);
		DECLARE p_typename VARCHAR(50);
		DECLARE p_segmentname VARCHAR(50);
		DECLARE p_groupname VARCHAR(50);
		DECLARE Voucher_Type VARCHAR(50);
		DECLARE gross_amt VARCHAR(50);
		DECLARE P_order VARCHAR(50);

		SELECT dcwno,VoucherType
		INTO @P_order,@Voucher_Type
		FROM proratamaterial
		WHERE proratamaterial.masterid= NEW.masterid;

		SELECT branchname,RegionName,Franchisename
		INTO @branch_name,@Region_Name,@Franchise_name
		FROM view_rbrs
		WHERE view_rbrs.Franchisecode = NEW.FranchiseCode;

		SELECT pdescription,ptypename,psegmentname,pgroupname
		INTO @p_description,@p_typename,@p_segmentname,@p_groupname
		FROM view_productdtetails
		WHERE view_productdtetails.pcode = NEW.ProductCode;

		SET @gross_amt = NEW.Amount + NEW.taxvalue;

		INSERT INTO r_purchasereport
		(regionname, branchname, franchisecode, franchisename, purchasenumber, purchasedate, PO, productcode, productdes, pgroupname, psegmentname, ptypename, vouchertype, quantity, NetAmount, taxamount, grossamt, unique_id)
		VALUES
		(@Region_Name,@branch_name,NEW.FranchiseCode,@Franchise_name,NEW.pmno,NEW.pmdatedate,@P_order,NEW.ProductCode,@p_description,@p_groupname,@p_segmentname,@p_typename,@Voucher_Type,NEW.Quantity,NEW.Amount,NEW.taxvalue,@gross_amt,NEW.masterid);
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `after_pmpurchase_update`;
DELIMITER //
CREATE TRIGGER `after_pmpurchase_update` AFTER UPDATE ON `proratamaterial_details`
 FOR EACH ROW BEGIN
		DELETE FROM r_purchasereport WHERE unique_id = NEW.masterid;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `after_pmpurchase_delete`;
DELIMITER //
CREATE TRIGGER `after_pmpurchase_delete` AFTER DELETE ON `proratamaterial_details`
 FOR EACH ROW BEGIN
		DELETE FROM r_purchasereport WHERE unique_id = OLD.masterid;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE IF NOT EXISTS `purchase` (
  `PO` varchar(100) DEFAULT NULL,
  `PurchaseNumber` varchar(50) NOT NULL DEFAULT '',
  `Purchasedate` date DEFAULT NULL,
  `ARBLWarehouseName` varchar(50) DEFAULT NULL,
  `Narration` varchar(200) DEFAULT NULL,
  `TotalPurchaseAmt` double DEFAULT NULL,
  `FranchiseCode` varchar(50) DEFAULT NULL,
  `VoucherType` varchar(50) DEFAULT NULL,
  `schemename` varchar(50) DEFAULT NULL,
  `voucherstatus` varchar(30) DEFAULT NULL,
  `masterid` varchar(45) NOT NULL,
  PRIMARY KEY (`masterid`),
  UNIQUE KEY `masterid` (`masterid`),
  KEY `PurchaseNumber` (`PurchaseNumber`),
  KEY `index_4` (`PurchaseNumber`,`Purchasedate`,`FranchiseCode`,`voucherstatus`,`VoucherType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `purchase`
--

INSERT INTO `purchase` (`PO`, `PurchaseNumber`, `Purchasedate`, `ARBLWarehouseName`, `Narration`, `TotalPurchaseAmt`, `FranchiseCode`, `VoucherType`, `schemename`, `voucherstatus`, `masterid`) VALUES
('', '1-RP-RAC9991', '2014-04-01', 'Supplier Ledger', '', 600, 'RAC9991', 'Regular Purchase', '', 'ACTIVE', '1-RAC9991-1-Apr-2014'),
('', '1-RP-RAC9992', '2014-04-01', 'Supplier Ledger', '', 0, 'RAC9992', 'Regular Purchase', '', 'ACTIVE', '1-RAC9992-1-Apr-2014'),
('PO/2014-15/2-6351019', 'RP/4/14-15-RP-6351019', '2015-03-26', 'Supplier', '', 635200, '6351019', 'Regular Purchase', '', 'ACTIVE', '11-6351019-26-Mar-2015'),
(NULL, 'ScrP/1/14-15-SRP-6351019', '2015-03-26', 'Supplier', '', 15880, '6351019', 'Scrap Purchase', '', 'ACTIVE', '16-6351019-26-Mar-2015'),
('', 'RP/1/14-15-RP-1432434', '2014-04-01', 'Supplier', '', 1000, '1432434', 'Regular Purchase', '', 'ACTIVE', '2-1432434-1-Apr-2014'),
('1-6351019', 'RP/1/14-15-RP-6351019', '2015-02-10', 'Supplier', '', 63520, '6351019', 'Regular Purchase', '', 'ACTIVE', '2-6351019-10-Feb-2015'),
('', 'RP/5/14-15-RP-6351019', '2015-03-26', 'Supplier', '', 33348, '6351019', 'Regular Purchase', '', 'ACTIVE', '21-6351019-26-Mar-2015'),
('', 'RP/6/14-15-RP-6351019', '2015-03-27', 'Supplier', '', 43352.4, '6351019', 'Regular Purchase', '', 'ACTIVE', '26-6351019-27-Mar-2015'),
('', 'RP/7/14-15-RP-6351019', '2015-03-27', 'Supplier', '', 33348, '6351019', 'Regular Purchase', '', 'ACTIVE', '27-6351019-27-Mar-2015'),
('', '2-RP-RAC9992', '2014-04-01', 'Supplier Ledger', '', 30000, 'RAC9992', 'Regular Purchase', '', 'ACTIVE', '3-RAC9992-1-Apr-2014'),
('', '2-RP-RAC9991', '2014-04-01', 'Supplier Ledger', '', 2000, 'RAC9991', 'Regular Purchase', '', 'ACTIVE', '4-RAC9991-1-Apr-2014'),
('', '3-RP-RAC9992', '2014-04-01', 'Supplier Ledger', '', 68720, 'RAC9992', 'Regular Purchase', '', 'ACTIVE', '6-RAC9992-1-Apr-2014'),
('', 'RP/2/14-15-RP-6351019', '2015-02-10', 'Supplier', '', 31760, '6351019', 'Regular Purchase', '', 'ACTIVE', '7-6351019-10-Feb-2015'),
('', 'RP/3/14-15-RP-6351019', '2015-02-10', 'Supplier', '', 31760, '6351019', 'Regular Purchase', '', 'ACTIVE', '8-6351019-10-Feb-2015'),
('', '4-RP-RAC9992', '2014-04-01', 'Supplier Ledger', '', 98220, 'RAC9992', 'Regular Purchase', '', 'ACTIVE', '8-RAC9992-1-Apr-2014'),
('', '5-RP-RAC9992', '2014-04-01', 'Supplier Ledger', '', 106720, 'RAC9992', 'Regular Purchase', '', 'ACTIVE', '9-RAC9992-1-Apr-2014');

-- --------------------------------------------------------

--
-- Table structure for table `purchasebatterymaster`
--

CREATE TABLE IF NOT EXISTS `purchasebatterymaster` (
  `purchasesNo` varchar(50) DEFAULT NULL,
  `Productcode` varchar(50) DEFAULT NULL,
  `Batteryno` varchar(50) DEFAULT NULL,
  `vochertype` varchar(50) DEFAULT NULL,
  `masterid` varchar(45) NOT NULL,
  KEY `index_1` (`masterid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `purchasebatterymaster`
--

INSERT INTO `purchasebatterymaster` (`purchasesNo`, `Productcode`, `Batteryno`, `vochertype`, `masterid`) VALUES
('RP/1/14-15-RP-6351019', 'PRODUCT40', 'Primary Batch', 'Regular Purchase', '2-6351019-10-Feb-2015'),
('RP/1/14-15-RP-6351019', 'PRODUCT30', 'Primary Batch', 'Regular Purchase', '2-6351019-10-Feb-2015'),
('RP/2/14-15-RP-6351019', 'PRODUCT30', 'Primary Batch', 'Regular Purchase', '7-6351019-10-Feb-2015'),
('1-RP-RAC9992', 'RAC014', 'Primary Batch', 'Regular Purchase', '1-RAC9992-1-Apr-2014'),
('1-RP-RAC9991', 'RAC002', 'Primary Batch', 'Regular Purchase', '1-RAC9991-1-Apr-2014'),
('2-RP-RAC9992', 'RAC014', 'Primary Batch', 'Regular Purchase', '3-RAC9992-1-Apr-2014'),
('2-RP-RAC9992', 'RAC012', 'Primary Batch', 'Regular Purchase', '3-RAC9992-1-Apr-2014'),
('3-RP-RAC9992', 'RAC014', 'Primary Batch', 'Regular Purchase', '6-RAC9992-1-Apr-2014'),
('3-RP-RAC9992', 'RAC012', 'Primary Batch', 'Regular Purchase', '6-RAC9992-1-Apr-2014'),
('3-RP-RAC9992', 'RAC001', 'Primary Batch', 'Regular Purchase', '6-RAC9992-1-Apr-2014'),
('3-RP-RAC9992', 'RAC012', 'Primary Batch', 'Regular Purchase', '6-RAC9992-1-Apr-2014'),
('4-RP-RAC9992', 'RAC014', 'Primary Batch', 'Regular Purchase', '8-RAC9992-1-Apr-2014'),
('4-RP-RAC9992', 'RAC012', 'Primary Batch', 'Regular Purchase', '8-RAC9992-1-Apr-2014'),
('4-RP-RAC9992', 'RAC001', 'Primary Batch', 'Regular Purchase', '8-RAC9992-1-Apr-2014'),
('4-RP-RAC9992', 'RAC012', 'Primary Batch', 'Regular Purchase', '8-RAC9992-1-Apr-2014'),
('4-RP-RAC9992', 'RAC001', 'Primary Batch', 'Regular Purchase', '8-RAC9992-1-Apr-2014'),
('4-RP-RAC9992', 'RAC007', 'Primary Batch', 'Regular Purchase', '8-RAC9992-1-Apr-2014'),
('5-RP-RAC9992', 'RAC014', 'Primary Batch', 'Regular Purchase', '9-RAC9992-1-Apr-2014'),
('5-RP-RAC9992', 'RAC012', 'Primary Batch', 'Regular Purchase', '9-RAC9992-1-Apr-2014'),
('5-RP-RAC9992', 'RAC001', 'Primary Batch', 'Regular Purchase', '9-RAC9992-1-Apr-2014'),
('5-RP-RAC9992', 'RAC012', 'Primary Batch', 'Regular Purchase', '9-RAC9992-1-Apr-2014'),
('5-RP-RAC9992', 'RAC001', 'Primary Batch', 'Regular Purchase', '9-RAC9992-1-Apr-2014'),
('5-RP-RAC9992', 'RAC007', 'Primary Batch', 'Regular Purchase', '9-RAC9992-1-Apr-2014'),
('5-RP-RAC9992', 'RAC006', 'Primary Batch', 'Regular Purchase', '9-RAC9992-1-Apr-2014'),
('2-RP-RAC9991', 'RAC014', 'Primary Batch', 'Regular Purchase', '4-RAC9991-1-Apr-2014'),
('RP/3/14-15-RP-6351019', 'PRODUCT40', 'Primary Batch', 'Regular Purchase', '8-6351019-10-Feb-2015'),
('RP/1/14-15-RP-1432434', 'RAC014', 'Primary Batch', 'Regular Purchase', '2-1432434-1-Apr-2014'),
('RP/4/14-15-RP-6351019', 'PRODUCT40', 'Primary Batch', 'Regular Purchase', '11-6351019-26-Mar-2015'),
('RP/4/14-15-RP-6351019', 'PRODUCT30', 'Primary Batch', 'Regular Purchase', '11-6351019-26-Mar-2015'),
('ScrP/1/14-15-SRP-6351019', 'PRODUCT30', 'Primary Batch', 'Scrap Purchase', '16-6351019-26-Mar-2015'),
('ScrP/1/14-15-SRP-6351019', 'PRODUCT30', '', 'Scrap Purchase', '16-6351019-26-Mar-2015'),
('RP/5/14-15-RP-6351019', 'PRODUCT30', 'Primary Batch', 'Regular Purchase', '21-6351019-26-Mar-2015'),
('RP/6/14-15-RP-6351019', 'PRODUCT40', 'Primary Batch', 'Regular Purchase', '26-6351019-27-Mar-2015'),
('RP/7/14-15-RP-6351019', 'PRODUCT40', 'Primary Batch', 'Regular Purchase', '27-6351019-27-Mar-2015');

-- --------------------------------------------------------

--
-- Table structure for table `purchasedetails_finalview`
--

CREATE TABLE IF NOT EXISTS `purchasedetails_finalview` (
  `PurchaseNumber` varchar(50) DEFAULT NULL,
  `PurchaseDate` date DEFAULT NULL,
  `ProductCode` varchar(50) DEFAULT NULL,
  `ProductDescription` varchar(50) DEFAULT NULL,
  `Quantity` int(30) DEFAULT NULL,
  `rate` double DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `FranchiseCode` varchar(50) DEFAULT NULL,
  `Franchisename` varchar(50) DEFAULT NULL,
  `Taxledger` varchar(50) DEFAULT NULL,
  `TaxAmount` double DEFAULT NULL,
  `Branch` varchar(50) DEFAULT NULL,
  `Region` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Stand-in structure for view `purchasedetails_view`
--
CREATE TABLE IF NOT EXISTS `purchasedetails_view` (
`PurchaseNumber` varchar(50)
,`PurchaseDate` date
,`ProductCode` varchar(50)
,`ProductDescription` varchar(50)
,`Quantity` int(30)
,`rate` double
,`amount` double
,`FranchiseCode` varchar(50)
,`Taxledger` varchar(50)
,`TaxAmount` double
);
-- --------------------------------------------------------

--
-- Table structure for table `purchaseledger`
--

CREATE TABLE IF NOT EXISTS `purchaseledger` (
  `Purchaseno` varchar(50) DEFAULT NULL,
  `Purchasedate` date DEFAULT NULL,
  `Taxledger` varchar(50) DEFAULT NULL,
  `ledgertaxvalue` double NOT NULL DEFAULT '0',
  `Taxamount` double DEFAULT NULL,
  `franchisecode` varchar(20) DEFAULT NULL,
  `voucherstatus` varchar(30) DEFAULT NULL,
  `masterid` varchar(45) NOT NULL,
  KEY `index_1` (`masterid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `purchaseledger`
--

INSERT INTO `purchaseledger` (`Purchaseno`, `Purchasedate`, `Taxledger`, `ledgertaxvalue`, `Taxamount`, `franchisecode`, `voucherstatus`, `masterid`) VALUES
('RP/5/14-15-RP-6351019', '2015-03-26', 'Input VAT @ 5%', 5, 1588, '6351019', 'ACTIVE', '21-6351019-26-Mar-2015'),
('RP/6/14-15-RP-6351019', '2015-03-27', 'Input VAT @ 5%', 5, 2064.4, '6351019', 'ACTIVE', '26-6351019-27-Mar-2015'),
('RP/7/14-15-RP-6351019', '2015-03-27', 'Input VAT @ 5%', 5, 1588, '6351019', 'ACTIVE', '27-6351019-27-Mar-2015');

-- --------------------------------------------------------

--
-- Table structure for table `purchaseorder`
--

CREATE TABLE IF NOT EXISTS `purchaseorder` (
  `PurchaseNumber` varchar(50) DEFAULT NULL,
  `PurchaseorderNo` varchar(50) NOT NULL DEFAULT '',
  `Purchasedate` date DEFAULT NULL,
  `ARBLWarehouseName` varchar(50) DEFAULT NULL,
  `Narration` varchar(200) DEFAULT NULL,
  `TotalPurchaseAmt` double DEFAULT NULL,
  `FranchiseCode` varchar(50) DEFAULT NULL,
  `VoucherType` varchar(50) DEFAULT NULL,
  `voucherstatus` varchar(30) DEFAULT NULL,
  `masterid` varchar(45) NOT NULL,
  PRIMARY KEY (`masterid`) USING BTREE,
  UNIQUE KEY `masterid` (`masterid`),
  KEY `index_3` (`PurchaseorderNo`,`Purchasedate`,`FranchiseCode`,`VoucherType`,`voucherstatus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `purchaseorder`
--

INSERT INTO `purchaseorder` (`PurchaseNumber`, `PurchaseorderNo`, `Purchasedate`, `ARBLWarehouseName`, `Narration`, `TotalPurchaseAmt`, `FranchiseCode`, `VoucherType`, `voucherstatus`, `masterid`) VALUES
('1-PO-6351019', '1-6351019', '2015-02-10', 'Supplier', '', 63520, '6351019', 'Purchase Order', 'ACTIVE', '1-6351019-10-Feb-2015'),
('PO/2014-15/2-PO-6351019', 'PO/2014-15/2-6351019', '2015-03-26', 'Supplier', '', 635200, '6351019', 'Purchase Order', 'ACTIVE', '10-6351019-26-Mar-2015'),
('PO/2014-15/3-PO-6351019', 'PO/2014-15/3-6351019', '2015-03-26', 'Dhamu', '', 3334.8, '6351019', 'Purchase Order', 'ACTIVE', '20-6351019-26-Mar-2015');

-- --------------------------------------------------------

--
-- Table structure for table `purchaseorderledger`
--

CREATE TABLE IF NOT EXISTS `purchaseorderledger` (
  `Purchaseno` varchar(50) DEFAULT NULL,
  `PurchaseOrderno` int(50) DEFAULT NULL,
  `Purchasedate` date DEFAULT NULL,
  `Taxledger` varchar(50) DEFAULT NULL,
  `Taxamount` double DEFAULT NULL,
  `franchisecode` varchar(20) DEFAULT NULL,
  `voucherstatus` varchar(20) DEFAULT NULL,
  `masterid` varchar(45) NOT NULL,
  KEY `index_1` (`masterid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `purchaseorderledger`
--

INSERT INTO `purchaseorderledger` (`Purchaseno`, `PurchaseOrderno`, `Purchasedate`, `Taxledger`, `Taxamount`, `franchisecode`, `voucherstatus`, `masterid`) VALUES
('PO/2014-15/3-PO-6351019', 0, '2015-03-26', 'Input VAT @ 5%', 158.8, '6351019', 'ACTIVE', '20-6351019-26-Mar-2015');

-- --------------------------------------------------------

--
-- Table structure for table `purchaseorder_details`
--

CREATE TABLE IF NOT EXISTS `purchaseorder_details` (
  `ProductCode` varchar(50) DEFAULT NULL,
  `ProductDescription` varchar(50) DEFAULT NULL,
  `Quantity` int(30) DEFAULT NULL,
  `Rate` double DEFAULT NULL,
  `Amount` float DEFAULT NULL,
  `PurchaseNumber` varchar(50) DEFAULT NULL,
  `PurchaseoderNo` varchar(50) DEFAULT NULL,
  `PurchaseDate` date DEFAULT NULL,
  `FranchiseCode` varchar(50) DEFAULT NULL,
  `voucherstatus` varchar(20) DEFAULT NULL,
  `masterid` varchar(45) NOT NULL,
  KEY `index_1` (`ProductCode`,`masterid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `purchaseorder_details`
--

INSERT INTO `purchaseorder_details` (`ProductCode`, `ProductDescription`, `Quantity`, `Rate`, `Amount`, `PurchaseNumber`, `PurchaseoderNo`, `PurchaseDate`, `FranchiseCode`, `voucherstatus`, `masterid`) VALUES
('PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', 10, 3176, 31760, '1-PO-6351019', '1-6351019', '2015-02-10', '6351019', 'ACTIVE', '1-6351019-10-Feb-2015'),
('PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', 10, 3176, 31760, '1-PO-6351019', '1-6351019', '2015-02-10', '6351019', 'ACTIVE', '1-6351019-10-Feb-2015'),
('PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', 1, 3176, 3176, 'PO/2014-15/3-PO-6351019', 'PO/2014-15/3-6351019', '2015-03-26', '6351019', 'ACTIVE', '20-6351019-26-Mar-2015'),
('PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', 100, 3176, 317600, 'PO/2014-15/2-PO-6351019', 'PO/2014-15/2-6351019', '2015-03-26', '6351019', 'ACTIVE', '10-6351019-26-Mar-2015'),
('PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', 100, 3176, 317600, 'PO/2014-15/2-PO-6351019', 'PO/2014-15/2-6351019', '2015-03-26', '6351019', 'ACTIVE', '10-6351019-26-Mar-2015');

--
-- Triggers `purchaseorder_details`
--
DROP TRIGGER IF EXISTS `after_po_insert`;
DELIMITER //
CREATE TRIGGER `after_po_insert` AFTER INSERT ON `purchaseorder_details`
 FOR EACH ROW BEGIN
		DECLARE Region_Name VARCHAR(50);
		DECLARE branch_name VARCHAR(50);
		DECLARE Franchise_name VARCHAR(50);
		DECLARE p_description VARCHAR(100);
		DECLARE p_typename VARCHAR(50);
		DECLARE p_segmentname VARCHAR(50);
		DECLARE p_groupname VARCHAR(50);	
		
		SELECT branchname,RegionName,Franchisename 
		INTO @branch_name,@Region_Name,@Franchise_name 
		FROM view_rbrs
		WHERE view_rbrs.Franchisecode = NEW.FranchiseCode;

		SELECT pdescription,ptypename,psegmentname,pgroupname 
		INTO @p_description,@p_typename,@p_segmentname,@p_groupname 
		FROM view_productdtetails
		WHERE view_productdtetails.pcode = NEW.ProductCode;
		
		INSERT INTO r_purchaseorder 
		(RegionName, branchname, FranchiseCode, Franchisename, vouchernumber, PurchaseOrderNo, PurchaseOrderDate, pgroupname, psegmentname, ptypename, ProductCode, ProductDescription, OrderQty, unique_id)
		VALUES
		(@Region_Name,@branch_name,NEW.FranchiseCode,@Franchise_name,NEW.PurchaseNumber,NEW.PurchaseoderNo,NEW.PurchaseDate,@p_groupname,@p_segmentname,@p_typename,NEW.ProductCode,@p_description,NEW.Quantity,NEW.masterid);
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `after_po_update`;
DELIMITER //
CREATE TRIGGER `after_po_update` AFTER UPDATE ON `purchaseorder_details`
 FOR EACH ROW BEGIN
		DELETE FROM r_purchaseorder WHERE unique_id = NEW.masterid;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `after_po_delete`;
DELIMITER //
CREATE TRIGGER `after_po_delete` AFTER DELETE ON `purchaseorder_details`
 FOR EACH ROW BEGIN
		DELETE FROM r_purchaseorder WHERE unique_id = OLD.masterid;	
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `purchasereturn`
--

CREATE TABLE IF NOT EXISTS `purchasereturn` (
  `PurchaseReturnNumber` varchar(50) NOT NULL DEFAULT '',
  `Purchasereturndate` date DEFAULT NULL,
  `ARBLWarehouseName` varchar(50) DEFAULT NULL,
  `Narration` varchar(200) DEFAULT NULL,
  `TotalPurchaseRetAmt` double DEFAULT NULL,
  `FranchiseCode` varchar(50) DEFAULT NULL,
  `VoucherType` varchar(50) DEFAULT NULL,
  `schemename` varchar(50) DEFAULT NULL,
  `voucherstatus` varchar(30) DEFAULT NULL,
  `masterid` varchar(45) NOT NULL,
  PRIMARY KEY (`masterid`),
  UNIQUE KEY `masterid` (`masterid`),
  KEY `PurchaseReturnNumber` (`PurchaseReturnNumber`),
  KEY `index_4` (`Purchasereturndate`,`FranchiseCode`,`VoucherType`,`voucherstatus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `purchasereturn`
--

INSERT INTO `purchasereturn` (`PurchaseReturnNumber`, `Purchasereturndate`, `ARBLWarehouseName`, `Narration`, `TotalPurchaseRetAmt`, `FranchiseCode`, `VoucherType`, `schemename`, `voucherstatus`, `masterid`) VALUES
('PRet/2/14-15-PR-6351019', '2015-03-26', 'Supplier', '', 31760, '6351019', 'Purchase Return', '', 'ACTIVE', '15-6351019-26-Mar-2015'),
('PRet/3/14-15-PR-6351019', '2015-03-26', 'Supplier', '', 6669.6, '6351019', 'Purchase Return', '', 'ACTIVE', '22-6351019-26-Mar-2015'),
('PRet/1/14-15-PR-6351019', '2015-02-10', 'Supplier', '', 15880, '6351019', 'Purchase Return', '', 'ACTIVE', '3-6351019-10-Feb-2015');

-- --------------------------------------------------------

--
-- Table structure for table `purchasereturnbatterymaster`
--

CREATE TABLE IF NOT EXISTS `purchasereturnbatterymaster` (
  `purchasesretNo` varchar(50) DEFAULT NULL,
  `Productcode` varchar(50) DEFAULT NULL,
  `Batteryno` varchar(50) DEFAULT NULL,
  `vochertype` varchar(50) DEFAULT NULL,
  `masterid` varchar(45) NOT NULL,
  KEY `index_1` (`masterid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `purchasereturnbatterymaster`
--

INSERT INTO `purchasereturnbatterymaster` (`purchasesretNo`, `Productcode`, `Batteryno`, `vochertype`, `masterid`) VALUES
('PRet/1/14-15-PR-6351019', 'PRODUCT30', 'Primary Batch', 'Purchase Return', '3-6351019-10-Feb-2015'),
('PRet/2/14-15-PR-6351019', '', 'Primary Batch', 'Purchase Return', '15-6351019-26-Mar-2015'),
('PRet/2/14-15-PR-6351019', '', 'Primary Batch', 'Purchase Return', '15-6351019-26-Mar-2015'),
('PRet/3/14-15-PR-6351019', 'PRODUCT30', 'Primary Batch', 'Purchase Return', '22-6351019-26-Mar-2015');

-- --------------------------------------------------------

--
-- Table structure for table `purchasereturn_details`
--

CREATE TABLE IF NOT EXISTS `purchasereturn_details` (
  `ProductCode` varchar(50) DEFAULT NULL,
  `ProductDescription` varchar(50) DEFAULT NULL,
  `Quantity` int(30) DEFAULT NULL,
  `taxvalue` double DEFAULT '0',
  `Rate` double DEFAULT NULL,
  `Amount` float DEFAULT NULL,
  `PurchaseRetNumber` varchar(50) DEFAULT NULL,
  `RetDate` date DEFAULT NULL,
  `FranchiseCode` varchar(50) DEFAULT NULL,
  `voucherstatus` varchar(50) DEFAULT NULL,
  `masterid` varchar(45) NOT NULL,
  KEY `index_1` (`ProductCode`,`FranchiseCode`,`masterid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `purchasereturn_details`
--

INSERT INTO `purchasereturn_details` (`ProductCode`, `ProductDescription`, `Quantity`, `taxvalue`, `Rate`, `Amount`, `PurchaseRetNumber`, `RetDate`, `FranchiseCode`, `voucherstatus`, `masterid`) VALUES
('PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', 5, 0, 3176, 15880, 'PRet/1/14-15-PR-6351019', '2015-02-10', '6351019', 'ACTIVE', '3-6351019-10-Feb-2015'),
('PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', 5, 0, 3176, 15880, 'PRet/2/14-15-PR-6351019', '2015-03-26', '6351019', 'ACTIVE', '15-6351019-26-Mar-2015'),
('PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', 5, 0, 3176, 15880, 'PRet/2/14-15-PR-6351019', '2015-03-26', '6351019', 'ACTIVE', '15-6351019-26-Mar-2015'),
('PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', 2, 317.6, 3176, 6352, 'PRet/3/14-15-PR-6351019', '2015-03-26', '6351019', 'ACTIVE', '22-6351019-26-Mar-2015');

--
-- Triggers `purchasereturn_details`
--
DROP TRIGGER IF EXISTS `after_purchasereturn_insert`;
DELIMITER //
CREATE TRIGGER `after_purchasereturn_insert` AFTER INSERT ON `purchasereturn_details`
 FOR EACH ROW BEGIN
		DECLARE Region_Name VARCHAR(50);
		DECLARE branch_name VARCHAR(50);
		DECLARE Franchise_name VARCHAR(50);
		DECLARE p_description VARCHAR(100);
		DECLARE p_typename VARCHAR(50);
		DECLARE p_segmentname VARCHAR(50);
		DECLARE p_groupname VARCHAR(50);
		DECLARE Voucher_Type VARCHAR(50);
		DECLARE gross_amt VARCHAR(50);

		SELECT VoucherType
		INTO @Voucher_Type
		FROM purchasereturn
		WHERE purchasereturn.masterid= NEW.masterid;

		SELECT branchname,RegionName,Franchisename
		INTO @branch_name,@Region_Name,@Franchise_name
		FROM view_rbrs
		WHERE view_rbrs.Franchisecode = NEW.FranchiseCode;

		SELECT pdescription,ptypename,psegmentname,pgroupname
		INTO @p_description,@p_typename,@p_segmentname,@p_groupname
		FROM view_productdtetails
		WHERE view_productdtetails.pcode = NEW.ProductCode;

		SET @gross_amt = NEW.Amount + NEW.taxvalue;

		INSERT INTO r_purchasereturn
		(regionname,branchname,franchisecode,franchisename,purchaseRetnumber,purchaseRetdate,productcode,pgroupname,psegmentname,ptypename,vouchertype,quantity,NetAmount,taxamount,grossamt,unique_id)
		VALUES
		(@Region_Name,@branch_name,NEW.FranchiseCode,@Franchise_name,NEW.PurchaseRetNumber,NEW.RetDate,NEW.ProductCode,@p_groupname,@p_segmentname,@p_typename,@Voucher_Type,NEW.Quantity,NEW.Amount,NEW.taxvalue,@gross_amt,NEW.masterid);
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `after_purchasereturn_update`;
DELIMITER //
CREATE TRIGGER `after_purchasereturn_update` AFTER UPDATE ON `purchasereturn_details`
 FOR EACH ROW BEGIN
		DELETE FROM r_purchasereturn WHERE unique_id = NEW.masterid;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `after_purchasereturn_delete`;
DELIMITER //
CREATE TRIGGER `after_purchasereturn_delete` AFTER DELETE ON `purchasereturn_details`
 FOR EACH ROW BEGIN
		DELETE FROM r_purchasereturn WHERE unique_id = OLD.masterid;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_details`
--

CREATE TABLE IF NOT EXISTS `purchase_details` (
  `ProductCode` varchar(50) DEFAULT NULL,
  `ProductDescription` varchar(50) DEFAULT NULL,
  `Quantity` int(30) DEFAULT NULL,
  `taxvalue` double NOT NULL DEFAULT '0',
  `Rate` double DEFAULT NULL,
  `Amount` float DEFAULT NULL,
  `PurchaseNumber` varchar(50) DEFAULT NULL,
  `PurchaseDate` date DEFAULT NULL,
  `FranchiseCode` varchar(50) DEFAULT NULL,
  `voucherstatus` varchar(30) DEFAULT NULL,
  `masterid` varchar(45) NOT NULL,
  KEY `index_1` (`ProductCode`,`masterid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `purchase_details`
--

INSERT INTO `purchase_details` (`ProductCode`, `ProductDescription`, `Quantity`, `taxvalue`, `Rate`, `Amount`, `PurchaseNumber`, `PurchaseDate`, `FranchiseCode`, `voucherstatus`, `masterid`) VALUES
('PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', 10, 0, 3176, 31760, 'RP/1/14-15-RP-6351019', '2015-02-10', '6351019', 'ACTIVE', '2-6351019-10-Feb-2015'),
('PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', 10, 0, 3176, 31760, 'RP/1/14-15-RP-6351019', '2015-02-10', '6351019', 'ACTIVE', '2-6351019-10-Feb-2015'),
('PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', 10, 0, 3176, 31760, 'RP/2/14-15-RP-6351019', '2015-02-10', '6351019', 'ACTIVE', '7-6351019-10-Feb-2015'),
('RAC014', 'ALPHA', 20, 0, 0, 0, '1-RP-RAC9992', '2014-04-01', 'RAC9992', 'ACTIVE', '1-RAC9992-1-Apr-2014'),
('RAC002', 'ETERNO 2(10L-35L)', 30, 0, 20, 600, '1-RP-RAC9991', '2014-04-01', 'RAC9991', 'ACTIVE', '1-RAC9991-1-Apr-2014'),
('RAC014', 'ALPHA', 20, 0, 1000, 20000, '2-RP-RAC9992', '2014-04-01', 'RAC9992', 'ACTIVE', '3-RAC9992-1-Apr-2014'),
('RAC012', 'OMEGA MAX 8', 100, 0, 100, 10000, '2-RP-RAC9992', '2014-04-01', 'RAC9992', 'ACTIVE', '3-RAC9992-1-Apr-2014'),
('RAC014', 'ALPHA', 20, 0, 1000, 20000, '3-RP-RAC9992', '2014-04-01', 'RAC9992', 'ACTIVE', '6-RAC9992-1-Apr-2014'),
('RAC012', 'OMEGA MAX 8', 100, 0, 100, 10000, '3-RP-RAC9992', '2014-04-01', 'RAC9992', 'ACTIVE', '6-RAC9992-1-Apr-2014'),
('RAC001', 'ETERNO DG(15L AND 25L)', 30, 0, 320, 9600, '3-RP-RAC9992', '2014-04-01', 'RAC9992', 'ACTIVE', '6-RAC9992-1-Apr-2014'),
('RAC012', 'OMEGA MAX 8', 32, 0, 910, 29120, '3-RP-RAC9992', '2014-04-01', 'RAC9992', 'ACTIVE', '6-RAC9992-1-Apr-2014'),
('RAC014', 'ALPHA', 20, 0, 1000, 20000, '4-RP-RAC9992', '2014-04-01', 'RAC9992', 'ACTIVE', '8-RAC9992-1-Apr-2014'),
('RAC012', 'OMEGA MAX 8', 100, 0, 100, 10000, '4-RP-RAC9992', '2014-04-01', 'RAC9992', 'ACTIVE', '8-RAC9992-1-Apr-2014'),
('RAC001', 'ETERNO DG(15L AND 25L)', 30, 0, 320, 9600, '4-RP-RAC9992', '2014-04-01', 'RAC9992', 'ACTIVE', '8-RAC9992-1-Apr-2014'),
('RAC012', 'OMEGA MAX 8', 32, 0, 910, 29120, '4-RP-RAC9992', '2014-04-01', 'RAC9992', 'ACTIVE', '8-RAC9992-1-Apr-2014'),
('RAC001', 'ETERNO DG(15L AND 25L)', 40, 0, 650, 26000, '4-RP-RAC9992', '2014-04-01', 'RAC9992', 'ACTIVE', '8-RAC9992-1-Apr-2014'),
('RAC007', 'PRONTO(6L)', 10, 0, 350, 3500, '4-RP-RAC9992', '2014-04-01', 'RAC9992', 'ACTIVE', '8-RAC9992-1-Apr-2014'),
('RAC014', 'ALPHA', 20, 0, 1000, 20000, '5-RP-RAC9992', '2014-04-01', 'RAC9992', 'ACTIVE', '9-RAC9992-1-Apr-2014'),
('RAC012', 'OMEGA MAX 8', 100, 0, 100, 10000, '5-RP-RAC9992', '2014-04-01', 'RAC9992', 'ACTIVE', '9-RAC9992-1-Apr-2014'),
('RAC001', 'ETERNO DG(15L AND 25L)', 30, 0, 320, 9600, '5-RP-RAC9992', '2014-04-01', 'RAC9992', 'ACTIVE', '9-RAC9992-1-Apr-2014'),
('RAC012', 'OMEGA MAX 8', 32, 0, 910, 29120, '5-RP-RAC9992', '2014-04-01', 'RAC9992', 'ACTIVE', '9-RAC9992-1-Apr-2014'),
('RAC001', 'ETERNO DG(15L AND 25L)', 40, 0, 650, 26000, '5-RP-RAC9992', '2014-04-01', 'RAC9992', 'ACTIVE', '9-RAC9992-1-Apr-2014'),
('RAC007', 'PRONTO(6L)', 10, 0, 350, 3500, '5-RP-RAC9992', '2014-04-01', 'RAC9992', 'ACTIVE', '9-RAC9992-1-Apr-2014'),
('RAC006', 'CDR', 10, 0, 850, 8500, '5-RP-RAC9992', '2014-04-01', 'RAC9992', 'ACTIVE', '9-RAC9992-1-Apr-2014'),
('RAC014', 'ALPHA', 100, 0, 20, 2000, '2-RP-RAC9991', '2014-04-01', 'RAC9991', 'ACTIVE', '4-RAC9991-1-Apr-2014'),
('PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', 10, 0, 3176, 31760, 'RP/3/14-15-RP-6351019', '2015-02-10', '6351019', 'ACTIVE', '8-6351019-10-Feb-2015'),
('RAC014', 'ALPHA', 10, 0, 100, 1000, 'RP/1/14-15-RP-1432434', '2014-04-01', '1432434', 'ACTIVE', '2-1432434-1-Apr-2014'),
('PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', 100, 0, 3176, 317600, 'RP/4/14-15-RP-6351019', '2015-03-26', '6351019', 'ACTIVE', '11-6351019-26-Mar-2015'),
('PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', 100, 0, 3176, 317600, 'RP/4/14-15-RP-6351019', '2015-03-26', '6351019', 'ACTIVE', '11-6351019-26-Mar-2015'),
('PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', 5, 0, 3176, 15880, 'ScrP/1/14-15-SRP-6351019', '2015-03-26', '6351019', 'ACTIVE', '16-6351019-26-Mar-2015'),
('PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', 10, 1588, 3176, 31760, 'RP/5/14-15-RP-6351019', '2015-03-26', '6351019', 'ACTIVE', '21-6351019-26-Mar-2015'),
('PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', 13, 2064.4, 3176, 41288, 'RP/6/14-15-RP-6351019', '2015-03-27', '6351019', 'ACTIVE', '26-6351019-27-Mar-2015'),
('PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', 10, 1588, 3176, 31760, 'RP/7/14-15-RP-6351019', '2015-03-27', '6351019', 'ACTIVE', '27-6351019-27-Mar-2015');

--
-- Triggers `purchase_details`
--
DROP TRIGGER IF EXISTS `after_purchase_insert`;
DELIMITER //
CREATE TRIGGER `after_purchase_insert` AFTER INSERT ON `purchase_details`
 FOR EACH ROW BEGIN
		DECLARE Region_Name VARCHAR(50);
		DECLARE branch_name VARCHAR(50);
		DECLARE Franchise_name VARCHAR(50);
		DECLARE p_description VARCHAR(100);
		DECLARE p_typename VARCHAR(50);
		DECLARE p_segmentname VARCHAR(50);
		DECLARE p_groupname VARCHAR(50);
		DECLARE Voucher_Type VARCHAR(50);
		DECLARE gross_amt VARCHAR(50);
		DECLARE P_order VARCHAR(50);

		SELECT PO,VoucherType
		INTO @P_order,@Voucher_Type
		FROM purchase
		WHERE purchase.masterid= NEW.masterid;

		SELECT branchname,RegionName,Franchisename
		INTO @branch_name,@Region_Name,@Franchise_name
		FROM view_rbrs
		WHERE view_rbrs.Franchisecode = NEW.FranchiseCode;

		SELECT pdescription,ptypename,psegmentname,pgroupname
		INTO @p_description,@p_typename,@p_segmentname,@p_groupname
		FROM view_productdtetails
		WHERE view_productdtetails.pcode = NEW.ProductCode;

		SET @gross_amt = NEW.Amount + NEW.taxvalue;

		INSERT INTO r_purchasereport
		(regionname, branchname, franchisecode, franchisename, purchasenumber, purchasedate, PO, productcode, productdes, pgroupname, psegmentname, ptypename, vouchertype, quantity, NetAmount, taxamount, grossamt, unique_id)
		VALUES
		(@Region_Name,@branch_name,NEW.FranchiseCode,@Franchise_name,NEW.PurchaseNumber,NEW.PurchaseDate,@P_order,NEW.ProductCode,@p_description,@p_groupname,@p_segmentname,@p_typename,@Voucher_Type,NEW.Quantity,NEW.Amount,NEW.taxvalue,@gross_amt,NEW.masterid);
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `after_purchase_update`;
DELIMITER //
CREATE TRIGGER `after_purchase_update` AFTER UPDATE ON `purchase_details`
 FOR EACH ROW BEGIN
		DELETE FROM r_purchasereport WHERE unique_id = NEW.masterid;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `after_purchase_delete`;
DELIMITER //
CREATE TRIGGER `after_purchase_delete` AFTER DELETE ON `purchase_details`
 FOR EACH ROW BEGIN
		DELETE FROM r_purchasereport WHERE unique_id = OLD.masterid;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `purchasreturnledger`
--

CREATE TABLE IF NOT EXISTS `purchasreturnledger` (
  `PurchaseRetno` varchar(50) DEFAULT NULL,
  `PurchaseRetdate` date DEFAULT NULL,
  `Taxledger` varchar(50) DEFAULT NULL,
  `Taxamount` double DEFAULT NULL,
  `franchisecode` varchar(20) DEFAULT NULL,
  `voucherstatus` varchar(30) DEFAULT NULL,
  `masterid` varchar(45) NOT NULL,
  `ledgertaxvalue` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `purchasreturnledger`
--

INSERT INTO `purchasreturnledger` (`PurchaseRetno`, `PurchaseRetdate`, `Taxledger`, `Taxamount`, `franchisecode`, `voucherstatus`, `masterid`, `ledgertaxvalue`) VALUES
('PRet/3/14-15-PR-6351019', '2015-03-26', 'Input VAT @ 5%', 317.6, '6351019', 'ACTIVE', '22-6351019-26-Mar-2015', 5);

-- --------------------------------------------------------

--
-- Stand-in structure for view `pwview`
--
CREATE TABLE IF NOT EXISTS `pwview` (
`masterid` varchar(45)
,`pricelevel` varchar(100)
,`blsno` varchar(50)
,`Newproductcode` varchar(50)
,`replacetype` varchar(50)
,`dcstatus` varchar(20)
,`replacevocherno` varchar(50)
,`replacedate` date
,`billqty` int(11) unsigned
,`ProrataAmt` double
,`Taxamount` double(17,0)
,`discount` double(17,0)
,`franchisecode` varchar(75)
,`CustomerName` varchar(75)
);
-- --------------------------------------------------------

--
-- Table structure for table `region`
--

CREATE TABLE IF NOT EXISTS `region` (
  `RegionCode` varchar(15) NOT NULL DEFAULT '',
  `RegionName` varchar(50) DEFAULT NULL,
  `CountryName` varchar(50) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `m_date` datetime DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`RegionCode`),
  KEY `Id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51 ;

--
-- Dumping data for table `region`
--

INSERT INTO `region` (`RegionCode`, `RegionName`, `CountryName`, `user_id`, `m_date`, `id`) VALUES
('Central', 'Hyderabad', 'IN', 'admin', '2013-05-02 16:37:47', 32),
('COO', 'COO-Hyderabad', 'IN', 'admin', '2013-06-05 17:13:13', 42),
('East1', 'Kolkata', 'IN', 'admin', '2013-06-04 15:33:44', 37),
('East2', 'Patna', 'IN', 'admin', '2013-07-05 17:51:20', 50),
('North1', 'Delhi', 'IN', '', '0000-00-00 00:00:00', 35),
('North2', 'Chandigarh', 'IN', '', '0000-00-00 00:00:00', 36),
('South1', 'Bangalore', 'IN', '', '0000-00-00 00:00:00', 33),
('South2', 'Chennai', 'IN', '', '0000-00-00 00:00:00', 38),
('West1', 'Mumbai', 'IN', '', '0000-00-00 00:00:00', 34),
('West2', 'Ahmedabad', 'IN', '', '0000-00-00 00:00:00', 40);

-- --------------------------------------------------------

--
-- Table structure for table `reportrights`
--

CREATE TABLE IF NOT EXISTS `reportrights` (
  `userid` varchar(50) NOT NULL DEFAULT '',
  `r_screen` varchar(50) NOT NULL,
  `access_right` varchar(10) NOT NULL,
  `usertype` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `reportrights`
--

INSERT INTO `reportrights` (`userid`, `r_screen`, `access_right`, `usertype`) VALUES
('admin', 'Serial Number History', 'Yes', 'Corporate'),
('tiara', 'Serial Number History', 'Yes', 'Corporate'),
('tally', 'Serial Number History', 'No', 'Corporate'),
('admin', 'Data Exchange', 'Yes', 'Corporate'),
('tiara', 'Data Exchange', 'Yes', 'Corporate'),
('tally', 'Data Exchange', 'Yes', 'Corporate'),
('admin', 'Purchase Order', 'Yes', 'Corporate'),
('tiara', 'Purchase Order', 'Yes', 'Corporate'),
('tally', 'Purchase Order', 'Yes', 'Corporate'),
('admin', 'Purchase Report', 'Yes', 'Corporate'),
('tiara', 'Purchase Report', 'Yes', 'Corporate'),
('tally', 'Purchase Report', 'Yes', 'Corporate'),
('admin', 'Purchase Summary', 'Yes', 'Corporate'),
('tiara', 'Purchase Summary', 'Yes', 'Corporate'),
('tally', 'Purchase Summary', 'Yes', 'Corporate'),
('admin', 'Purchase Returns', 'Yes', 'Corporate'),
('tiara', 'Purchase Returns', 'Yes', 'Corporate'),
('tally', 'Purchase Returns', 'Yes', 'Corporate'),
('admin', 'Sales Register', 'Yes', 'Corporate'),
('tiara', 'Sales Register', 'Yes', 'Corporate'),
('tally', 'Sales Register', 'Yes', 'Corporate'),
('admin', 'Sales Report', 'Yes', 'Corporate'),
('tiara', 'Sales Report', 'Yes', 'Corporate'),
('tally', 'Sales Report', 'Yes', 'Corporate'),
('admin', 'Weekly Sales Report', 'Yes', 'Corporate'),
('tiara', 'Weekly Sales Report', 'Yes', 'Corporate'),
('tally', 'Weekly Sales Report', 'Yes', 'Corporate'),
('admin', 'Retailer Category Detailed', 'Yes', 'Corporate'),
('tiara', 'Retailer Category Detailed', 'Yes', 'Corporate'),
('tally', 'Retailer Category Detailed', 'Yes', 'Corporate'),
('admin', 'Retailer Category Summary', 'Yes', 'Corporate'),
('tiara', 'Retailer Category Summary', 'Yes', 'Corporate'),
('tally', 'Retailer Category Summary', 'Yes', 'Corporate'),
('admin', 'Sales Returns', 'Yes', 'Corporate'),
('tiara', 'Sales Returns', 'Yes', 'Corporate'),
('tally', 'Sales Returns', 'Yes', 'Corporate'),
('admin', 'Stock Ledger', 'Yes', 'Corporate'),
('tiara', 'Stock Ledger', 'Yes', 'Corporate'),
('tally', 'Stock Ledger', 'Yes', 'Corporate'),
('admin', 'ServiceCallRegister', 'Yes', 'Corporate'),
('tiara', 'ServiceCallRegister', 'Yes', 'Corporate'),
('tally', 'ServiceCallRegister', 'No', 'Corporate'),
('admin', 'Warranty Administration', 'Yes', 'Corporate'),
('tiara', 'Warranty Administration', 'Yes', 'Corporate'),
('tally', 'Warranty Administration', 'No', 'Corporate'),
('admin', 'Serial Number History', 'Yes', 'Corporate'),
('admin', 'Data Exchange', 'Yes', 'Corporate'),
('admin', 'Purchase Order', 'Yes', 'Corporate'),
('admin', 'Purchase Report', 'Yes', 'Corporate'),
('admin', 'Purchase Summary', 'Yes', 'Corporate'),
('admin', 'Purchase Returns', 'Yes', 'Corporate'),
('admin', 'Sales Register', 'Yes', 'Corporate'),
('admin', 'Sales Report', 'Yes', 'Corporate'),
('admin', 'Weekly Sales Report', 'Yes', 'Corporate'),
('admin', 'Retailer Category Detailed', 'Yes', 'Corporate'),
('admin', 'Retailer Category Summary', 'Yes', 'Corporate'),
('admin', 'Sales Returns', 'Yes', 'Corporate'),
('admin', 'Stock Ledger', 'Yes', 'Corporate'),
('admin', 'ServiceCallRegister', 'Yes', 'Corporate'),
('admin', 'Warranty Administration', 'Yes', 'Corporate'),
('admin', 'Item Wise Sales Summary Report', 'Yes', 'Corporate'),
('admin', 'Sales Summary report', 'Yes', 'Corporate'),
('admin', 'Division Wise', 'Yes', 'Corporate'),
('admin', 'Zone State Sales Summary', 'Yes', 'Corporate'),
('admin', 'Sales with Sales Return Report', 'Yes', 'Corporate'),
('admin', 'Sub-Product Wise Sales Report', 'Yes', 'Corporate'),
('admin', 'Purchase with Purchase Return Report', 'Yes', 'Corporate'),
('tiara', 'Serial Number History', 'No', 'Others'),
('tiara', 'Data Exchange', 'No', 'Others'),
('tiara', 'Purchase Order', 'No', 'Others'),
('tiara', 'Purchase Report', 'No', 'Others'),
('tiara', 'Purchase Summary', 'No', 'Others'),
('tiara', 'Purchase Returns', 'No', 'Others'),
('tiara', 'Sales Register', 'No', 'Others'),
('tiara', 'Sales Report', 'No', 'Others'),
('tiara', 'Weekly Sales Report', 'No', 'Others'),
('tiara', 'Retailer Category Detailed', 'No', 'Others'),
('tiara', 'Retailer Category Summary', 'No', 'Others'),
('tiara', 'Sales Returns', 'No', 'Others'),
('tiara', 'Stock Ledger', 'No', 'Others'),
('tiara', 'ServiceCallRegister', 'No', 'Others'),
('tiara', 'Warranty Administration', 'No', 'Others'),
('tiara', 'Item Wise Sales Summary Report', 'No', 'Others'),
('tiara', 'Sales Summary report', 'No', 'Others'),
('tiara', 'Division Wise', 'No', 'Others'),
('tiara', 'Zone State Sales Summary', 'No', 'Others'),
('tiara', 'Sales with Sales Return Report', 'No', 'Others'),
('tiara', 'Sub-Product Wise Sales Report', 'No', 'Others'),
('tiara', 'Purchase with Purchase Return Report', 'No', 'Others'),
('tally', 'Serial Number History', 'No', 'Corporate'),
('tally', 'Data Exchange', 'Yes', 'Corporate'),
('tally', 'Purchase Order', 'Yes', 'Corporate'),
('tally', 'Purchase Report', 'Yes', 'Corporate'),
('tally', 'Purchase Summary', 'Yes', 'Corporate'),
('tally', 'Purchase Returns', 'Yes', 'Corporate'),
('tally', 'Sales Register', 'Yes', 'Corporate'),
('tally', 'Sales Report', 'Yes', 'Corporate'),
('tally', 'Weekly Sales Report', 'Yes', 'Corporate'),
('tally', 'Retailer Category Detailed', 'Yes', 'Corporate'),
('tally', 'Retailer Category Summary', 'Yes', 'Corporate'),
('tally', 'Sales Returns', 'Yes', 'Corporate'),
('tally', 'Stock Ledger', 'Yes', 'Corporate'),
('tally', 'ServiceCallRegister', 'No', 'Corporate'),
('tally', 'Warranty Administration', 'No', 'Corporate'),
('tally', 'Item Wise Sales Summary Report', 'No', 'Corporate'),
('tally', 'Sales Summary report', 'Yes', 'Corporate'),
('tally', 'Division Wise', 'Yes', 'Corporate'),
('tally', 'Zone State Sales Summary', 'Yes', 'Corporate'),
('tally', 'Sales with Sales Return Report', 'Yes', 'Corporate'),
('tally', 'Sub-Product Wise Sales Report', 'Yes', 'Corporate'),
('tally', 'Purchase with Purchase Return Report', 'Yes', 'Corporate'),
('admin', 'Serial Number History', 'Yes', 'Corporate'),
('admin', 'Data Exchange', 'Yes', 'Corporate'),
('admin', 'Purchase Order', 'Yes', 'Corporate'),
('admin', 'Purchase Report', 'Yes', 'Corporate'),
('admin', 'Purchase Summary', 'Yes', 'Corporate'),
('admin', 'Purchase Returns', 'Yes', 'Corporate'),
('admin', 'Sales Register', 'Yes', 'Corporate'),
('admin', 'Sales Report', 'Yes', 'Corporate'),
('admin', 'Weekly Sales Report', 'Yes', 'Corporate'),
('admin', 'Retailer Category Detailed', 'Yes', 'Corporate'),
('admin', 'Retailer Category Summary', 'Yes', 'Corporate'),
('admin', 'Sales Returns', 'Yes', 'Corporate'),
('admin', 'Stock Ledger', 'Yes', 'Corporate'),
('admin', 'ServiceCallRegister', 'Yes', 'Corporate'),
('admin', 'Warranty Administration', 'Yes', 'Corporate'),
('admin', 'Item Wise Sales Summary Report', 'Yes', 'Corporate'),
('admin', 'Sales Summary report', 'Yes', 'Corporate'),
('admin', 'Division Wise', 'Yes', 'Corporate'),
('admin', 'Zone State Sales Summary', 'Yes', 'Corporate'),
('admin', 'Sales with Sales Return Report', 'Yes', 'Corporate'),
('admin', 'Sub-Product Wise Sales Report', 'Yes', 'Corporate'),
('admin', 'Purchase with Purchase Return Report', 'Yes', 'Corporate'),
('tiara', 'Serial Number History', 'No', 'Others'),
('tiara', 'Data Exchange', 'No', 'Others'),
('tiara', 'Purchase Order', 'No', 'Others'),
('tiara', 'Purchase Report', 'No', 'Others'),
('tiara', 'Purchase Summary', 'No', 'Others'),
('tiara', 'Purchase Returns', 'No', 'Others'),
('tiara', 'Sales Register', 'No', 'Others'),
('tiara', 'Sales Report', 'No', 'Others'),
('tiara', 'Weekly Sales Report', 'No', 'Others'),
('tiara', 'Retailer Category Detailed', 'No', 'Others'),
('tiara', 'Retailer Category Summary', 'No', 'Others'),
('tiara', 'Sales Returns', 'No', 'Others'),
('tiara', 'Stock Ledger', 'No', 'Others'),
('tiara', 'ServiceCallRegister', 'No', 'Others'),
('tiara', 'Warranty Administration', 'No', 'Others'),
('tiara', 'Item Wise Sales Summary Report', 'No', 'Others'),
('tiara', 'Sales Summary report', 'No', 'Others'),
('tiara', 'Division Wise', 'No', 'Others'),
('tiara', 'Zone State Sales Summary', 'No', 'Others'),
('tiara', 'Sales with Sales Return Report', 'No', 'Others'),
('tiara', 'Sub-Product Wise Sales Report', 'No', 'Others'),
('tiara', 'Purchase with Purchase Return Report', 'No', 'Others'),
('tally', 'Serial Number History', 'No', 'Corporate'),
('tally', 'Data Exchange', 'Yes', 'Corporate'),
('tally', 'Purchase Order', 'Yes', 'Corporate'),
('tally', 'Purchase Report', 'Yes', 'Corporate'),
('tally', 'Purchase Summary', 'Yes', 'Corporate'),
('tally', 'Purchase Returns', 'Yes', 'Corporate'),
('tally', 'Sales Register', 'Yes', 'Corporate'),
('tally', 'Sales Report', 'Yes', 'Corporate'),
('tally', 'Weekly Sales Report', 'Yes', 'Corporate'),
('tally', 'Retailer Category Detailed', 'Yes', 'Corporate'),
('tally', 'Retailer Category Summary', 'Yes', 'Corporate'),
('tally', 'Sales Returns', 'Yes', 'Corporate'),
('tally', 'Stock Ledger', 'Yes', 'Corporate'),
('tally', 'ServiceCallRegister', 'No', 'Corporate'),
('tally', 'Warranty Administration', 'No', 'Corporate'),
('tally', 'Item Wise Sales Summary Report', 'No', 'Corporate'),
('tally', 'Sales Summary report', 'Yes', 'Corporate'),
('tally', 'Division Wise', 'Yes', 'Corporate'),
('tally', 'Zone State Sales Summary', 'Yes', 'Corporate'),
('tally', 'Sales with Sales Return Report', 'Yes', 'Corporate'),
('tally', 'Sub-Product Wise Sales Report', 'Yes', 'Corporate'),
('tally', 'Purchase with Purchase Return Report', 'Yes', 'Corporate'),
('Jayapremnath', 'Serial Number History', 'No', 'Others'),
('Jayapremnath', 'Data Exchange', 'No', 'Others'),
('Jayapremnath', 'Purchase Order', 'No', 'Others'),
('Jayapremnath', 'Purchase Report', 'No', 'Others'),
('Jayapremnath', 'Purchase Summary', 'No', 'Others'),
('Jayapremnath', 'Purchase Returns', 'No', 'Others'),
('Jayapremnath', 'Sales Register', 'No', 'Others'),
('Jayapremnath', 'Sales Report', 'No', 'Others'),
('Jayapremnath', 'Weekly Sales Report', 'No', 'Others'),
('Jayapremnath', 'Retailer Category Detailed', 'No', 'Others'),
('Jayapremnath', 'Retailer Category Summary', 'No', 'Others'),
('Jayapremnath', 'Sales Returns', 'No', 'Others'),
('Jayapremnath', 'Stock Ledger', 'No', 'Others'),
('Jayapremnath', 'ServiceCallRegister', 'No', 'Others'),
('Jayapremnath', 'Warranty Administration', 'No', 'Others'),
('Jayapremnath', 'Item Wise Sales Summary Report', 'No', 'Others'),
('Jayapremnath', 'Sales Summary report', 'No', 'Others'),
('Jayapremnath', 'Division Wise', 'No', 'Others'),
('Jayapremnath', 'Zone State Sales Summary', 'No', 'Others'),
('Jayapremnath', 'Sales with Sales Return Report', 'No', 'Others'),
('Jayapremnath', 'Sub-Product Wise Sales Report', 'No', 'Others'),
('Jayapremnath', 'Purchase with Purchase Return Report', 'No', 'Others'),
('Jayapremnath', 'Location Wise Stock Summary', 'No', 'Others'),
('Jayapremnath', 'Consolidated Stock Summary', 'No', 'Others'),
('Jayapremnath', 'Division Wise Closing Stock Report', 'No', 'Others'),
('Jayapremnath', 'Product Wise Category Wise Transaction Report', 'No', 'Others'),
('Jayapremnath', 'Stockist Monthly Comparison Report', 'No', 'Others'),
('Jayapremnath', 'Usage Web Log Report', 'No', 'Others'),
('Jayapremnath', 'Day Wise Synch Status - Master Upload', 'No', 'Others'),
('Jayapremnath', 'Day Wise Synch Status - Transaction Download', 'No', 'Others'),
('Jayapremnath', 'Month Wise Synch Status - Master Upload', 'No', 'Others'),
('Jayapremnath', 'Month Wise Synch Status - Transaction Download', 'No', 'Others');

-- --------------------------------------------------------

--
-- Table structure for table `reportrights_sub`
--

CREATE TABLE IF NOT EXISTS `reportrights_sub` (
  `userid` varchar(50) NOT NULL DEFAULT '',
  `branch` varchar(15) DEFAULT NULL,
  KEY `FK_reportrights_sub_1` (`branch`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `retailerbatterymaster`
--

CREATE TABLE IF NOT EXISTS `retailerbatterymaster` (
  `SalesNo` varchar(50) DEFAULT NULL,
  `Productcode` varchar(50) DEFAULT NULL,
  `Batteryno` varchar(50) DEFAULT NULL,
  `masterid` varchar(45) NOT NULL,
  KEY `index_1` (`masterid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `retailerbatterymaster`
--

INSERT INTO `retailerbatterymaster` (`SalesNo`, `Productcode`, `Batteryno`, `masterid`) VALUES
('RS/1/14-15-RS-6351019', 'PRODUCT40', 'Primary Batch', '6-6351019-10-Feb-2015'),
('RS/1/14-15-RS-6351019', 'PRODUCT30', 'Primary Batch', '6-6351019-10-Feb-2015'),
('1-RS-RAC9992', 'RAC004', 'Primary Batch', '2-RAC9992-1-Apr-2014'),
('1-RS-RAC9991', 'RAC002', 'Primary Batch', '2-RAC9991-1-Apr-2014'),
('2-RS-RAC9992', 'RAC004', 'Primary Batch', '4-RAC9992-1-Apr-2014'),
('2-RS-RAC9992', 'RAC010', 'Primary Batch', '4-RAC9992-1-Apr-2014'),
('3-RS-RAC9992', 'RAC004', 'Primary Batch', '5-RAC9992-1-Apr-2014'),
('3-RS-RAC9992', 'RAC010', 'Primary Batch', '5-RAC9992-1-Apr-2014'),
('3-RS-RAC9992', 'RAC006', 'Primary Batch', '5-RAC9992-1-Apr-2014'),
('3-RS-RAC9992', 'RAC002', 'Primary Batch', '5-RAC9992-1-Apr-2014'),
('4-RS-RAC9992', 'RAC004', 'Primary Batch', '7-RAC9992-1-Apr-2014'),
('4-RS-RAC9992', 'RAC006', 'Primary Batch', '7-RAC9992-1-Apr-2014'),
('4-RS-RAC9992', 'RAC007', 'Primary Batch', '7-RAC9992-1-Apr-2014'),
('4-RS-RAC9992', 'RAC012', 'Primary Batch', '7-RAC9992-1-Apr-2014'),
('5-RS-RAC9992', 'RAC004', 'Primary Batch', '10-RAC9992-1-Apr-2014'),
('5-RS-RAC9992', 'RAC006', 'Primary Batch', '10-RAC9992-1-Apr-2014'),
('5-RS-RAC9992', 'RAC007', 'Primary Batch', '10-RAC9992-1-Apr-2014'),
('5-RS-RAC9992', 'RAC012', 'Primary Batch', '10-RAC9992-1-Apr-2014'),
('5-RS-RAC9992', 'RAC015', 'Primary Batch', '10-RAC9992-1-Apr-2014'),
('2-RS-RAC9991', 'RAC014', 'Primary Batch', '3-RAC9991-1-Apr-2014'),
('1/14-15-RS-6351019', 'PRODUCT30', 'Primary Batch', '9-6351019-10-Feb-2015'),
('RS/2/14-15-RS-6351019', 'PRODUCT40', 'Primary Batch', '13-6351019-26-Mar-2015'),
('ScrS/1/14-15-SS-6351019', 'PRODUCT40', 'Primary Batch', '14-6351019-26-Mar-2015'),
('2/14-15-RS-6351019', 'PRODUCT30', 'Primary Batch', '18-6351019-26-Mar-2015'),
('3/14-15-RS-6351019', 'PRODUCT30', 'Primary Batch', '24-6351019-27-Mar-2015');

-- --------------------------------------------------------

--
-- Table structure for table `retailercategory`
--

CREATE TABLE IF NOT EXISTS `retailercategory` (
  `CategoryCode` varchar(50) NOT NULL DEFAULT '',
  `RetailerCategory` varchar(50) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `m_date` datetime DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`CategoryCode`),
  KEY `ID` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `retailercategoryupload`
--

CREATE TABLE IF NOT EXISTS `retailercategoryupload` (
  `Franchiseecode` varchar(30) DEFAULT NULL,
  `Masters` varchar(50) DEFAULT NULL,
  `Code` varchar(100) DEFAULT NULL,
  `Status` varchar(30) DEFAULT NULL,
  `InsertDate` date DEFAULT NULL,
  `Deliverydae` date DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `retailermaster`
--

CREATE TABLE IF NOT EXISTS `retailermaster` (
  `RetailerCode` varchar(15) NOT NULL DEFAULT '',
  `RetailerName` varchar(50) DEFAULT NULL,
  `Address` varchar(250) DEFAULT NULL,
  `City` varchar(50) DEFAULT NULL,
  `Districtname` varchar(50) DEFAULT NULL,
  `fmexecutive` varchar(50) DEFAULT NULL,
  `Category` varchar(50) DEFAULT NULL,
  `ContactName` varchar(50) DEFAULT NULL,
  `ContactNo` varchar(15) DEFAULT NULL,
  `CreditDays` int(11) DEFAULT '0',
  `CreditLimit` float DEFAULT '0',
  `TinNo` varchar(20) DEFAULT NULL,
  `TinDate` date DEFAULT NULL,
  `bankname` varchar(50) DEFAULT NULL,
  `branchname` varchar(50) DEFAULT NULL,
  `ifsccode` varchar(50) DEFAULT NULL,
  `accountholdersname` varchar(50) DEFAULT NULL,
  `accno` varchar(50) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `franchiseeme` varchar(45) DEFAULT NULL,
  `retailerclassification` varchar(45) DEFAULT NULL,
  `geographical` varchar(45) DEFAULT NULL,
  `retailercategory1` varchar(45) DEFAULT NULL,
  `retailercategory2` varchar(45) DEFAULT NULL,
  `retailercategory3` varchar(45) DEFAULT NULL,
  `m_date` datetime DEFAULT NULL,
  `id` int(11) NOT NULL,
  PRIMARY KEY (`RetailerCode`),
  KEY `ID` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `retailermaster`
--

INSERT INTO `retailermaster` (`RetailerCode`, `RetailerName`, `Address`, `City`, `Districtname`, `fmexecutive`, `Category`, `ContactName`, `ContactNo`, `CreditDays`, `CreditLimit`, `TinNo`, `TinDate`, `bankname`, `branchname`, `ifsccode`, `accountholdersname`, `accno`, `user_id`, `franchiseeme`, `retailerclassification`, `geographical`, `retailercategory1`, `retailercategory2`, `retailercategory3`, `m_date`, `id`) VALUES
('6351019 - 0001', 'Customer', '', '', '', '6351019', '', '', '', 0, 0, '', '0000-00-00', '', '', '', '', '', NULL, '', 'Amaron Dominant Retailer', 'Local', 'Regular Billing Counters', '', '', NULL, 0),
('RAC9991 - 0001', 'Customer Ledger', '', '', '', 'RAC9991', '', '', '', 0, 0, '', '0000-00-00', '', '', '', '', '', NULL, '', 'Amaron Dominant Retailer', 'Local', 'Regular Billing Counters', '', '', NULL, 0),
('RAC9992 - 0001', 'Customer Ledger', '', '', '', 'RAC9992', '', '', '', 0, 0, '', '0000-00-00', '', '', '', '', '', NULL, 'Marketing Exe 1', 'Others', 'Local', 'Regular Billing Counters', '', '', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `retailermasterupload`
--

CREATE TABLE IF NOT EXISTS `retailermasterupload` (
  `Franchiseecode` varchar(30) DEFAULT NULL,
  `Masters` varchar(50) DEFAULT NULL,
  `Code` varchar(100) DEFAULT NULL,
  `Status` varchar(30) DEFAULT NULL,
  `InsertDate` date DEFAULT NULL,
  `Deliverydae` date DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `retailersales`
--

CREATE TABLE IF NOT EXISTS `retailersales` (
  `salesno` varchar(50) NOT NULL DEFAULT '',
  `salesdate` date DEFAULT NULL,
  `retailername` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `phoneno` int(50) DEFAULT NULL,
  `narration` varchar(100) DEFAULT NULL,
  `totalamount` int(20) DEFAULT NULL,
  `franchisecode` varchar(50) DEFAULT NULL,
  `VoucherType` varchar(50) DEFAULT NULL,
  `schemename` varchar(50) DEFAULT NULL,
  `voucherstatus` varchar(30) DEFAULT NULL,
  `pricelevel` varchar(30) DEFAULT NULL,
  `masterid` varchar(45) NOT NULL,
  PRIMARY KEY (`masterid`) USING BTREE,
  UNIQUE KEY `masterid` (`masterid`),
  KEY `salesno` (`salesno`),
  KEY `new_index` (`salesdate`,`VoucherType`,`franchisecode`,`voucherstatus`),
  KEY `index_4` (`salesdate`,`retailername`,`franchisecode`,`VoucherType`,`voucherstatus`,`pricelevel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `retailersales`
--

INSERT INTO `retailersales` (`salesno`, `salesdate`, `retailername`, `address`, `city`, `phoneno`, `narration`, `totalamount`, `franchisecode`, `VoucherType`, `schemename`, `voucherstatus`, `pricelevel`, `masterid`) VALUES
('5-RS-RAC9992', '2014-04-01', 'Customer Ledger', NULL, NULL, NULL, '', 70000, 'RAC9992', 'Regular Sales', '', 'ACTIVE', 'Consumer Price', '10-RAC9992-1-Apr-2014'),
('RS/2/14-15-RS-6351019', '2015-03-26', 'Customer', NULL, NULL, NULL, '', 46211, '6351019', 'Regular Sales', '', 'ACTIVE', 'Retailer', '13-6351019-26-Mar-2015'),
('ScrS/1/14-15-SS-6351019', '2015-03-26', 'Customer', NULL, NULL, NULL, '', 29010, '6351019', 'Scrap Sales', '', 'ACTIVE', 'Retailer', '14-6351019-26-Mar-2015'),
('2/14-15-RS-6351019', '2015-03-26', 'Customer', NULL, NULL, NULL, '', 20980, '6351019', 'Regular Sales', '', 'ACTIVE', 'Institutional', '18-6351019-26-Mar-2015'),
('1-RS-RAC9991', '2014-04-01', 'Customer Ledger', NULL, NULL, NULL, '', 4000, 'RAC9991', 'Regular Sales', '', 'ACTIVE', 'Consumer Price', '2-RAC9991-1-Apr-2014'),
('1-RS-RAC9992', '2014-04-01', 'Customer Ledger', NULL, NULL, NULL, '', 30000, 'RAC9992', 'Regular Sales', '', 'ACTIVE', 'Consumer Price', '2-RAC9992-1-Apr-2014'),
('3/14-15-RS-6351019', '2015-03-27', 'Customer', NULL, NULL, NULL, '', 46211, '6351019', 'Regular Sales', '', 'ACTIVE', '', '24-6351019-27-Mar-2015'),
('2-RS-RAC9991', '2014-04-01', 'Customer Ledger', NULL, NULL, NULL, '', 9500, 'RAC9991', 'Regular Sales', '', 'ACTIVE', 'Consumer Price', '3-RAC9991-1-Apr-2014'),
('2-RS-RAC9992', '2014-04-01', 'Customer Ledger', NULL, NULL, NULL, '', 42000, 'RAC9992', 'Regular Sales', '', 'ACTIVE', 'Consumer Price', '4-RAC9992-1-Apr-2014'),
('3-RS-RAC9992', '2014-04-01', 'Customer Ledger', NULL, NULL, NULL, '', 63750, 'RAC9992', 'Regular Sales', '', 'ACTIVE', 'Consumer Price', '5-RAC9992-1-Apr-2014'),
('RS/1/14-15-RS-6351019', '2015-02-10', 'Customer', NULL, NULL, NULL, '', 60921, '6351019', 'Regular Sales', '', 'ACTIVE', 'Consumer Price', '6-6351019-10-Feb-2015'),
('4-RS-RAC9992', '2014-04-01', 'Customer Ledger', NULL, NULL, NULL, '', 64500, 'RAC9992', 'Regular Sales', '', 'ACTIVE', 'Consumer Price', '7-RAC9992-1-Apr-2014'),
('1/14-15-RS-6351019', '2015-02-10', 'Customer', NULL, NULL, NULL, '', 29010, '6351019', 'Regular Sales', '', 'ACTIVE', 'Consumer Price', '9-6351019-10-Feb-2015');

--
-- Triggers `retailersales`
--
DROP TRIGGER IF EXISTS `after_sales_update`;
DELIMITER //
CREATE TRIGGER `after_sales_update` AFTER UPDATE ON `retailersales`
 FOR EACH ROW BEGIN
		DELETE FROM r_salesreport WHERE unique_id = NEW.masterid;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `retailersalesitem`
--

CREATE TABLE IF NOT EXISTS `retailersalesitem` (
  `salesno` varchar(50) DEFAULT NULL,
  `salesdates` date DEFAULT NULL,
  `productcode` varchar(50) DEFAULT NULL,
  `productdes` varchar(100) DEFAULT NULL,
  `quantity` varchar(50) DEFAULT NULL,
  `taxvalue` double NOT NULL DEFAULT '0',
  `rate` int(50) DEFAULT NULL,
  `amount` int(60) DEFAULT NULL,
  `franchisecode` varchar(50) DEFAULT NULL,
  `voucherstatus` varchar(30) DEFAULT NULL,
  `masterid` varchar(45) NOT NULL,
  KEY `new_index` (`masterid`),
  KEY `index_1` (`productcode`,`franchisecode`,`masterid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `retailersalesitem`
--

INSERT INTO `retailersalesitem` (`salesno`, `salesdates`, `productcode`, `productdes`, `quantity`, `taxvalue`, `rate`, `amount`, `franchisecode`, `voucherstatus`, `masterid`) VALUES
('RS/1/14-15-RS-6351019', '2015-02-10', 'PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', '10', 1450.5, 2901, 29010, '6351019', 'ACTIVE', '6-6351019-10-Feb-2015'),
('RS/1/14-15-RS-6351019', '2015-02-10', 'PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', '10', 1450.5, 2901, 29010, '6351019', 'ACTIVE', '6-6351019-10-Feb-2015'),
('1-RS-RAC9992', '2014-04-01', 'RAC004', 'ALTRO 2(15L-50L)', '10', 0, 3000, 30000, 'RAC9992', 'ACTIVE', '2-RAC9992-1-Apr-2014'),
('1-RS-RAC9991', '2014-04-01', 'RAC002', 'ETERNO 2(10L-35L)', '100', 0, 40, 4000, 'RAC9991', 'ACTIVE', '2-RAC9991-1-Apr-2014'),
('2-RS-RAC9992', '2014-04-01', 'RAC004', 'ALTRO 2(15L-50L)', '10', 0, 3000, 30000, 'RAC9992', 'ACTIVE', '4-RAC9992-1-Apr-2014'),
('2-RS-RAC9992', '2014-04-01', 'RAC010', 'PRONTO(1L AND 3L)', '100', 0, 120, 12000, 'RAC9992', 'ACTIVE', '4-RAC9992-1-Apr-2014'),
('3-RS-RAC9992', '2014-04-01', 'RAC004', 'ALTRO 2(15L-50L)', '10', 0, 3000, 30000, 'RAC9992', 'ACTIVE', '5-RAC9992-1-Apr-2014'),
('3-RS-RAC9992', '2014-04-01', 'RAC010', 'PRONTO(1L AND 3L)', '100', 0, 120, 12000, 'RAC9992', 'ACTIVE', '5-RAC9992-1-Apr-2014'),
('3-RS-RAC9992', '2014-04-01', 'RAC006', 'CDR', '10', 0, 300, 3000, 'RAC9992', 'ACTIVE', '5-RAC9992-1-Apr-2014'),
('3-RS-RAC9992', '2014-04-01', 'RAC002', 'ETERNO 2(10L-35L)', '25', 0, 750, 18750, 'RAC9992', 'ACTIVE', '5-RAC9992-1-Apr-2014'),
('4-RS-RAC9992', '2014-04-01', 'RAC004', 'ALTRO 2(15L-50L)', '10', 0, 3000, 30000, 'RAC9992', 'ACTIVE', '7-RAC9992-1-Apr-2014'),
('4-RS-RAC9992', '2014-04-01', 'RAC006', 'CDR', '10', 0, 450, 4500, 'RAC9992', 'ACTIVE', '7-RAC9992-1-Apr-2014'),
('4-RS-RAC9992', '2014-04-01', 'RAC007', 'PRONTO(6L)', '30', 0, 500, 15000, 'RAC9992', 'ACTIVE', '7-RAC9992-1-Apr-2014'),
('4-RS-RAC9992', '2014-04-01', 'RAC012', 'OMEGA MAX 8', '25', 0, 600, 15000, 'RAC9992', 'ACTIVE', '7-RAC9992-1-Apr-2014'),
('5-RS-RAC9992', '2014-04-01', 'RAC004', 'ALTRO 2(15L-50L)', '10', 0, 3000, 30000, 'RAC9992', 'ACTIVE', '10-RAC9992-1-Apr-2014'),
('5-RS-RAC9992', '2014-04-01', 'RAC006', 'CDR', '10', 0, 450, 4500, 'RAC9992', 'ACTIVE', '10-RAC9992-1-Apr-2014'),
('5-RS-RAC9992', '2014-04-01', 'RAC007', 'PRONTO(6L)', '30', 0, 500, 15000, 'RAC9992', 'ACTIVE', '10-RAC9992-1-Apr-2014'),
('5-RS-RAC9992', '2014-04-01', 'RAC012', 'OMEGA MAX 8', '25', 0, 600, 15000, 'RAC9992', 'ACTIVE', '10-RAC9992-1-Apr-2014'),
('5-RS-RAC9992', '2014-04-01', 'RAC015', 'SOLAR COMMERCIAL', '10', 0, 550, 5500, 'RAC9992', 'ACTIVE', '10-RAC9992-1-Apr-2014'),
('2-RS-RAC9991', '2014-04-01', 'RAC014', 'ALPHA', '50', 0, 190, 9500, 'RAC9991', 'ACTIVE', '3-RAC9991-1-Apr-2014'),
('1/14-15-RS-6351019', '2015-02-10', 'PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', '10', 0, 2901, 29010, '6351019', 'ACTIVE', '9-6351019-10-Feb-2015'),
('RS/2/14-15-RS-6351019', '2015-03-26', 'PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', '10', 2200.5, 4401, 44010, '6351019', 'ACTIVE', '13-6351019-26-Mar-2015'),
('ScrS/1/14-15-SS-6351019', '2015-03-26', 'PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', '10', 0, 2901, 29010, '6351019', 'ACTIVE', '14-6351019-26-Mar-2015'),
('2/14-15-RS-6351019', '2015-03-26', 'PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', '10', 0, 2098, 20980, '6351019', 'ACTIVE', '18-6351019-26-Mar-2015'),
('3/14-15-RS-6351019', '2015-03-27', 'PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', '10', 2200.5, 4401, 44010, '6351019', 'ACTIVE', '24-6351019-27-Mar-2015');

--
-- Triggers `retailersalesitem`
--
DROP TRIGGER IF EXISTS `after_sales_insert`;
DELIMITER //
CREATE TRIGGER `after_sales_insert` AFTER INSERT ON `retailersalesitem`
 FOR EACH ROW BEGIN
		DECLARE Region_Name VARCHAR(50);
		DECLARE branch_name VARCHAR(50);
		DECLARE Franchise_name VARCHAR(50);
		DECLARE p_description VARCHAR(100);
		DECLARE p_typename VARCHAR(50);
		DECLARE p_segmentname VARCHAR(50);
		DECLARE p_groupname VARCHAR(50);		
		DECLARE retailer_name VARCHAR(50);
		DECLARE Voucher_Type VARCHAR(50);		
		DECLARE voucher_status VARCHAR(50);
		DECLARE price_level VARCHAR(50);
		DECLARE gross_amt VARCHAR(50);

		SELECT retailername,VoucherType,voucherstatus,pricelevel 
		INTO @retailer_name,@Voucher_Type,@voucher_status,@price_level
		FROM retailersales
		WHERE retailersales.masterid= NEW.masterid;
		
		SELECT branchname,RegionName,Franchisename 
		INTO @branch_name,@Region_Name,@Franchise_name 
		FROM view_rbrs
		WHERE view_rbrs.Franchisecode = NEW.franchisecode;

		SELECT pdescription,ptypename,psegmentname,pgroupname 
		INTO @p_description,@p_typename,@p_segmentname,@p_groupname 
		FROM view_productdtetails
		WHERE view_productdtetails.pcode = NEW.productcode;
		
		SET @gross_amt = NEW.amount + NEW.taxvalue;
		
		INSERT INTO r_salesreport 
		(regionname,branchname,franchisecode,franchisename,salesno,salesdates,retailername,productcode,productdes,pgroupname,psegmentname,ptypename,VoucherType,quantity,amount,TaxAmount,grossamt,voucherstatus,preicelevel,unique_id)
		VALUES
		(@Region_Name,@branch_name,NEW.franchisecode,@Franchise_name,NEW.salesno,NEW.salesdates,@retailer_name,NEW.productcode,@p_description,@p_groupname,@p_segmentname,@p_typename,@Voucher_Type,NEW.quantity,NEW.amount,NEW.taxvalue,@gross_amt,@voucher_status,@price_level,NEW.masterid);
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `retailersalesledger`
--

CREATE TABLE IF NOT EXISTS `retailersalesledger` (
  `SalesNo` varchar(100) DEFAULT NULL,
  `salesdates` date DEFAULT NULL,
  `taxledger` varchar(100) DEFAULT NULL,
  `taxamount` double DEFAULT NULL,
  `franchisecode` varchar(100) DEFAULT NULL,
  `voucherstatus` varchar(30) DEFAULT NULL,
  `masterid` varchar(45) NOT NULL,
  `ledgertaxvalue` double NOT NULL DEFAULT '8',
  KEY `index_1` (`masterid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `retailersalesledger`
--

INSERT INTO `retailersalesledger` (`SalesNo`, `salesdates`, `taxledger`, `taxamount`, `franchisecode`, `voucherstatus`, `masterid`, `ledgertaxvalue`) VALUES
('RS/1/14-15-RS-6351019', '2015-02-10', 'Output VAT@5%', 2901, '6351019', 'ACTIVE', '6-6351019-10-Feb-2015', 5),
('RS/2/14-15-RS-6351019', '2015-03-26', 'Output VAT@5%', 2200.5, '6351019', 'ACTIVE', '13-6351019-26-Mar-2015', 5),
('3/14-15-RS-6351019', '2015-03-27', 'Output VAT@5%', 2200.5, '6351019', 'ACTIVE', '24-6351019-27-Mar-2015', 5);

-- --------------------------------------------------------

--
-- Table structure for table `r_purchaseorder`
--

CREATE TABLE IF NOT EXISTS `r_purchaseorder` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `RegionName` varchar(50) DEFAULT NULL,
  `branchname` varchar(50) DEFAULT NULL,
  `FranchiseCode` varchar(50) DEFAULT NULL,
  `Franchisename` varchar(50) DEFAULT NULL,
  `vouchernumber` varchar(50) DEFAULT NULL,
  `PurchaseOrderNo` varchar(50) DEFAULT NULL,
  `PurchaseOrderDate` date DEFAULT NULL,
  `pgroupname` varchar(50) DEFAULT NULL,
  `psegmentname` varchar(50) DEFAULT NULL,
  `ptypename` varchar(50) DEFAULT NULL,
  `ProductCode` varchar(50) DEFAULT NULL,
  `ProductDescription` varchar(100) DEFAULT NULL,
  `OrderQty` varchar(50) DEFAULT NULL,
  `unique_id` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_2` (`PurchaseOrderDate`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `r_purchaseorder`
--

INSERT INTO `r_purchaseorder` (`id`, `RegionName`, `branchname`, `FranchiseCode`, `Franchisename`, `vouchernumber`, `PurchaseOrderNo`, `PurchaseOrderDate`, `pgroupname`, `psegmentname`, `ptypename`, `ProductCode`, `ProductDescription`, `OrderQty`, `unique_id`) VALUES
(1, 'Chennai', 'Coimbatore', '6351019', 'ABC AGENCIES', '1-PO-6351019', '1-6351019', '2015-02-10', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', '10', '1-6351019-10-Feb-2015'),
(2, 'Chennai', 'Coimbatore', '6351019', 'ABC AGENCIES', '1-PO-6351019', '1-6351019', '2015-02-10', 'PRODUCT GROUP SAMPLE 1', 'PS SAMPLE 1', 'PT SAMPLE 1', 'PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', '10', '1-6351019-10-Feb-2015'),
(5, 'Chennai', 'Coimbatore', '6351019', 'ABC AGENCIES', 'PO/2014-15/3-PO-6351019', 'PO/2014-15/3-6351019', '2015-03-26', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', '1', '20-6351019-26-Mar-2015'),
(6, 'Chennai', 'Coimbatore', '6351019', 'ABC AGENCIES', 'PO/2014-15/2-PO-6351019', 'PO/2014-15/2-6351019', '2015-03-26', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', '100', '10-6351019-26-Mar-2015'),
(7, 'Chennai', 'Coimbatore', '6351019', 'ABC AGENCIES', 'PO/2014-15/2-PO-6351019', 'PO/2014-15/2-6351019', '2015-03-26', 'PRODUCT GROUP SAMPLE 1', 'PS SAMPLE 1', 'PT SAMPLE 1', 'PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', '100', '10-6351019-26-Mar-2015');

-- --------------------------------------------------------

--
-- Table structure for table `r_purchasereport`
--

CREATE TABLE IF NOT EXISTS `r_purchasereport` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `regionname` varchar(50) DEFAULT NULL,
  `branchname` varchar(50) DEFAULT NULL,
  `franchisecode` varchar(50) DEFAULT NULL,
  `franchisename` varchar(50) DEFAULT NULL,
  `purchasenumber` varchar(50) DEFAULT NULL,
  `purchasedate` date DEFAULT NULL,
  `PO` varchar(50) DEFAULT NULL,
  `productcode` varchar(50) DEFAULT NULL,
  `productdes` varchar(100) DEFAULT NULL,
  `pgroupname` varchar(50) DEFAULT NULL,
  `psegmentname` varchar(50) DEFAULT NULL,
  `ptypename` varchar(50) DEFAULT NULL,
  `vouchertype` varchar(50) DEFAULT NULL,
  `quantity` varchar(50) DEFAULT NULL,
  `NetAmount` varchar(50) DEFAULT NULL,
  `taxamount` varchar(50) DEFAULT NULL,
  `grossamt` varchar(50) DEFAULT NULL,
  `unique_id` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_2` (`purchasedate`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

--
-- Dumping data for table `r_purchasereport`
--

INSERT INTO `r_purchasereport` (`id`, `regionname`, `branchname`, `franchisecode`, `franchisename`, `purchasenumber`, `purchasedate`, `PO`, `productcode`, `productdes`, `pgroupname`, `psegmentname`, `ptypename`, `vouchertype`, `quantity`, `NetAmount`, `taxamount`, `grossamt`, `unique_id`) VALUES
(1, 'Chennai', 'Coimbatore', '6351019', 'ABC AGENCIES', 'RP/1/14-15-RP-6351019', '2015-02-10', '1-6351019', 'PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Purchase', '10', '31760', '0', '31760', '2-6351019-10-Feb-2015'),
(2, 'Chennai', 'Coimbatore', '6351019', 'ABC AGENCIES', 'RP/1/14-15-RP-6351019', '2015-02-10', '1-6351019', 'PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', 'PRODUCT GROUP SAMPLE 1', 'PS SAMPLE 1', 'PT SAMPLE 1', 'Regular Purchase', '10', '31760', '0', '31760', '2-6351019-10-Feb-2015'),
(3, 'Chennai', 'Coimbatore', '6351019', 'ABC AGENCIES', 'RP/2/14-15-RP-6351019', '2015-02-10', '', 'PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', 'PRODUCT GROUP SAMPLE 1', 'PS SAMPLE 1', 'PT SAMPLE 1', 'Regular Purchase', '10', '31760', '0', '31760', '7-6351019-10-Feb-2015'),
(4, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '1-RP-RAC9992', '2014-04-01', '', 'RAC014', 'ALPHA', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Purchase', '20', '0', '0', '0', '1-RAC9992-1-Apr-2014'),
(5, 'Chennai', 'Chennai', 'RAC9991', 'GEORGE ENTERPRISES', '1-RP-RAC9991', '2014-04-01', '', 'RAC002', 'ETERNO 2(10L-35L)', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Purchase', '30', '600', '0', '600', '1-RAC9991-1-Apr-2014'),
(6, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '2-RP-RAC9992', '2014-04-01', '', 'RAC014', 'ALPHA', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Purchase', '20', '20000', '0', '20000', '3-RAC9992-1-Apr-2014'),
(7, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '2-RP-RAC9992', '2014-04-01', '', 'RAC012', 'OMEGA MAX 8', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Purchase', '100', '10000', '0', '10000', '3-RAC9992-1-Apr-2014'),
(8, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '3-RP-RAC9992', '2014-04-01', '', 'RAC014', 'ALPHA', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Purchase', '20', '20000', '0', '20000', '6-RAC9992-1-Apr-2014'),
(9, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '3-RP-RAC9992', '2014-04-01', '', 'RAC012', 'OMEGA MAX 8', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Purchase', '100', '10000', '0', '10000', '6-RAC9992-1-Apr-2014'),
(10, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '3-RP-RAC9992', '2014-04-01', '', 'RAC001', 'ETERNO DG(15L AND 25L)', 'PRODUCT GROUP SAMPLE 1', 'PS SAMPLE 1', 'PT SAMPLE 1', 'Regular Purchase', '30', '9600', '0', '9600', '6-RAC9992-1-Apr-2014'),
(11, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '3-RP-RAC9992', '2014-04-01', '', 'RAC012', 'OMEGA MAX 8', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Purchase', '32', '29120', '0', '29120', '6-RAC9992-1-Apr-2014'),
(12, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '4-RP-RAC9992', '2014-04-01', '', 'RAC014', 'ALPHA', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Purchase', '20', '20000', '0', '20000', '8-RAC9992-1-Apr-2014'),
(13, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '4-RP-RAC9992', '2014-04-01', '', 'RAC012', 'OMEGA MAX 8', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Purchase', '100', '10000', '0', '10000', '8-RAC9992-1-Apr-2014'),
(14, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '4-RP-RAC9992', '2014-04-01', '', 'RAC001', 'ETERNO DG(15L AND 25L)', 'PRODUCT GROUP SAMPLE 1', 'PS SAMPLE 1', 'PT SAMPLE 1', 'Regular Purchase', '30', '9600', '0', '9600', '8-RAC9992-1-Apr-2014'),
(15, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '4-RP-RAC9992', '2014-04-01', '', 'RAC012', 'OMEGA MAX 8', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Purchase', '32', '29120', '0', '29120', '8-RAC9992-1-Apr-2014'),
(16, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '4-RP-RAC9992', '2014-04-01', '', 'RAC001', 'ETERNO DG(15L AND 25L)', 'PRODUCT GROUP SAMPLE 1', 'PS SAMPLE 1', 'PT SAMPLE 1', 'Regular Purchase', '40', '26000', '0', '26000', '8-RAC9992-1-Apr-2014'),
(17, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '4-RP-RAC9992', '2014-04-01', '', 'RAC007', 'PRONTO(6L)', 'PRODUCT GROUP SAMPLE 1', 'PS SAMPLE 1', 'PT SAMPLE 1', 'Regular Purchase', '10', '3500', '0', '3500', '8-RAC9992-1-Apr-2014'),
(18, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '5-RP-RAC9992', '2014-04-01', '', 'RAC014', 'ALPHA', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Purchase', '20', '20000', '0', '20000', '9-RAC9992-1-Apr-2014'),
(19, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '5-RP-RAC9992', '2014-04-01', '', 'RAC012', 'OMEGA MAX 8', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Purchase', '100', '10000', '0', '10000', '9-RAC9992-1-Apr-2014'),
(20, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '5-RP-RAC9992', '2014-04-01', '', 'RAC001', 'ETERNO DG(15L AND 25L)', 'PRODUCT GROUP SAMPLE 1', 'PS SAMPLE 1', 'PT SAMPLE 1', 'Regular Purchase', '30', '9600', '0', '9600', '9-RAC9992-1-Apr-2014'),
(21, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '5-RP-RAC9992', '2014-04-01', '', 'RAC012', 'OMEGA MAX 8', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Purchase', '32', '29120', '0', '29120', '9-RAC9992-1-Apr-2014'),
(22, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '5-RP-RAC9992', '2014-04-01', '', 'RAC001', 'ETERNO DG(15L AND 25L)', 'PRODUCT GROUP SAMPLE 1', 'PS SAMPLE 1', 'PT SAMPLE 1', 'Regular Purchase', '40', '26000', '0', '26000', '9-RAC9992-1-Apr-2014'),
(23, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '5-RP-RAC9992', '2014-04-01', '', 'RAC007', 'PRONTO(6L)', 'PRODUCT GROUP SAMPLE 1', 'PS SAMPLE 1', 'PT SAMPLE 1', 'Regular Purchase', '10', '3500', '0', '3500', '9-RAC9992-1-Apr-2014'),
(24, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '5-RP-RAC9992', '2014-04-01', '', 'RAC006', 'CDR', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Purchase', '10', '8500', '0', '8500', '9-RAC9992-1-Apr-2014'),
(25, 'Chennai', 'Chennai', 'RAC9991', 'GEORGE ENTERPRISES', '2-RP-RAC9991', '2014-04-01', '', 'RAC014', 'ALPHA', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Purchase', '100', '2000', '0', '2000', '4-RAC9991-1-Apr-2014'),
(26, 'Chennai', 'Coimbatore', '6351019', 'ABC AGENCIES', 'RP/3/14-15-RP-6351019', '2015-02-10', '', 'PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Purchase', '10', '31760', '0', '31760', '8-6351019-10-Feb-2015'),
(27, 'Chennai', 'Coimbatore', '1432434', 'RAM  AND  CO', 'RP/1/14-15-RP-1432434', '2014-04-01', '', 'RAC014', 'ALPHA', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Purchase', '10', '1000', '0', '1000', '2-1432434-1-Apr-2014'),
(28, 'Chennai', 'Coimbatore', '6351019', 'ABC AGENCIES', 'RP/4/14-15-RP-6351019', '2015-03-26', 'PO/2014-15/2-6351019', 'PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Purchase', '100', '317600', '0', '317600', '11-6351019-26-Mar-2015'),
(29, 'Chennai', 'Coimbatore', '6351019', 'ABC AGENCIES', 'RP/4/14-15-RP-6351019', '2015-03-26', 'PO/2014-15/2-6351019', 'PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', 'PRODUCT GROUP SAMPLE 1', 'PS SAMPLE 1', 'PT SAMPLE 1', 'Regular Purchase', '100', '317600', '0', '317600', '11-6351019-26-Mar-2015'),
(30, 'Chennai', 'Coimbatore', '6351019', 'ABC AGENCIES', 'ScrP/1/14-15-SRP-6351019', '2015-03-26', NULL, 'PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', 'PRODUCT GROUP SAMPLE 1', 'PS SAMPLE 1', 'PT SAMPLE 1', 'Scrap Purchase', '5', '15880', '0', '15880', '16-6351019-26-Mar-2015'),
(31, 'Chennai', 'Coimbatore', '6351019', 'ABC AGENCIES', 'RP/5/14-15-RP-6351019', '2015-03-26', '', 'PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', 'PRODUCT GROUP SAMPLE 1', 'PS SAMPLE 1', 'PT SAMPLE 1', 'Regular Purchase', '10', '31760', '1588', '33348', '21-6351019-26-Mar-2015'),
(32, 'Chennai', 'Coimbatore', '6351019', 'ABC AGENCIES', 'RP/6/14-15-RP-6351019', '2015-03-27', '', 'PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Purchase', '13', '41288', '2064.4', '43352.4', '26-6351019-27-Mar-2015'),
(33, 'Chennai', 'Coimbatore', '6351019', 'ABC AGENCIES', 'RP/7/14-15-RP-6351019', '2015-03-27', '', 'PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Purchase', '10', '31760', '1588', '33348', '27-6351019-27-Mar-2015');

-- --------------------------------------------------------

--
-- Table structure for table `r_purchasereturn`
--

CREATE TABLE IF NOT EXISTS `r_purchasereturn` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `regionname` varchar(50) DEFAULT NULL,
  `branchname` varchar(50) DEFAULT NULL,
  `franchisecode` varchar(50) DEFAULT NULL,
  `franchisename` varchar(50) DEFAULT NULL,
  `purchaseRetnumber` varchar(50) DEFAULT NULL,
  `purchaseRetdate` date DEFAULT NULL,
  `productcode` varchar(50) DEFAULT NULL,
  `pgroupname` varchar(50) DEFAULT NULL,
  `psegmentname` varchar(50) DEFAULT NULL,
  `ptypename` varchar(50) DEFAULT NULL,
  `vouchertype` varchar(50) DEFAULT NULL,
  `quantity` varchar(50) DEFAULT NULL,
  `NetAmount` varchar(50) DEFAULT NULL,
  `taxamount` varchar(50) DEFAULT NULL,
  `grossamt` varchar(50) DEFAULT NULL,
  `unique_id` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_2` (`purchaseRetdate`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `r_purchasereturn`
--

INSERT INTO `r_purchasereturn` (`id`, `regionname`, `branchname`, `franchisecode`, `franchisename`, `purchaseRetnumber`, `purchaseRetdate`, `productcode`, `pgroupname`, `psegmentname`, `ptypename`, `vouchertype`, `quantity`, `NetAmount`, `taxamount`, `grossamt`, `unique_id`) VALUES
(1, 'Chennai', 'Coimbatore', '6351019', 'ABC AGENCIES', 'PRet/1/14-15-PR-6351019', '2015-02-10', 'PRODUCT40', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Purchase Return', '5', '15880', '0', '15880', '3-6351019-10-Feb-2015'),
(2, 'Chennai', 'Coimbatore', '6351019', 'ABC AGENCIES', 'PRet/2/14-15-PR-6351019', '2015-03-26', 'PRODUCT40', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Purchase Return', '5', '15880', '0', '15880', '15-6351019-26-Mar-2015'),
(3, 'Chennai', 'Coimbatore', '6351019', 'ABC AGENCIES', 'PRet/2/14-15-PR-6351019', '2015-03-26', 'PRODUCT30', 'PRODUCT GROUP SAMPLE 1', 'PS SAMPLE 1', 'PT SAMPLE 1', 'Purchase Return', '5', '15880', '0', '15880', '15-6351019-26-Mar-2015'),
(4, 'Chennai', 'Coimbatore', '6351019', 'ABC AGENCIES', 'PRet/3/14-15-PR-6351019', '2015-03-26', 'PRODUCT40', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Purchase Return', '2', '6352', '317.6', '6669.6', '22-6351019-26-Mar-2015');

-- --------------------------------------------------------

--
-- Table structure for table `r_salesreport`
--

CREATE TABLE IF NOT EXISTS `r_salesreport` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `regionname` varchar(50) DEFAULT NULL,
  `branchname` varchar(50) DEFAULT NULL,
  `franchisecode` varchar(50) DEFAULT NULL,
  `franchisename` varchar(50) DEFAULT NULL,
  `salesno` varchar(50) DEFAULT NULL,
  `salesdates` date DEFAULT NULL,
  `retailername` varchar(100) DEFAULT NULL,
  `productcode` varchar(50) DEFAULT NULL,
  `productdes` varchar(100) DEFAULT NULL,
  `pgroupname` varchar(50) DEFAULT NULL,
  `psegmentname` varchar(50) DEFAULT NULL,
  `ptypename` varchar(50) DEFAULT NULL,
  `VoucherType` varchar(50) DEFAULT NULL,
  `quantity` varchar(50) DEFAULT NULL,
  `amount` varchar(50) DEFAULT NULL,
  `TaxAmount` varchar(50) DEFAULT NULL,
  `grossamt` varchar(50) DEFAULT NULL,
  `voucherstatus` varchar(50) DEFAULT NULL,
  `preicelevel` varchar(50) DEFAULT NULL,
  `unique_id` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_2` (`salesdates`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `r_salesreport`
--

INSERT INTO `r_salesreport` (`id`, `regionname`, `branchname`, `franchisecode`, `franchisename`, `salesno`, `salesdates`, `retailername`, `productcode`, `productdes`, `pgroupname`, `psegmentname`, `ptypename`, `VoucherType`, `quantity`, `amount`, `TaxAmount`, `grossamt`, `voucherstatus`, `preicelevel`, `unique_id`) VALUES
(1, 'Chennai', 'Coimbatore', '6351019', 'ABC AGENCIES', 'RS/1/14-15-RS-6351019', '2015-02-10', 'Customer', 'PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Sales', '10', '29010', '1450.5', '30460.5', 'ACTIVE', 'Consumer Price', '6-6351019-10-Feb-2015'),
(2, 'Chennai', 'Coimbatore', '6351019', 'ABC AGENCIES', 'RS/1/14-15-RS-6351019', '2015-02-10', 'Customer', 'PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', 'PRODUCT GROUP SAMPLE 1', 'PS SAMPLE 1', 'PT SAMPLE 1', 'Regular Sales', '10', '29010', '1450.5', '30460.5', 'ACTIVE', 'Consumer Price', '6-6351019-10-Feb-2015'),
(3, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '1-RS-RAC9992', '2014-04-01', 'Customer Ledger', 'RAC004', 'ALTRO 2(15L-50L)', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Sales', '10', '30000', '0', '30000', 'ACTIVE', 'Consumer Price', '2-RAC9992-1-Apr-2014'),
(4, 'Chennai', 'Chennai', 'RAC9991', 'GEORGE ENTERPRISES', '1-RS-RAC9991', '2014-04-01', 'Customer Ledger', 'RAC002', 'ETERNO 2(10L-35L)', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Sales', '100', '4000', '0', '4000', 'ACTIVE', 'Consumer Price', '2-RAC9991-1-Apr-2014'),
(5, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '2-RS-RAC9992', '2014-04-01', 'Customer Ledger', 'RAC004', 'ALTRO 2(15L-50L)', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Sales', '10', '30000', '0', '30000', 'ACTIVE', 'Consumer Price', '4-RAC9992-1-Apr-2014'),
(6, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '2-RS-RAC9992', '2014-04-01', 'Customer Ledger', 'RAC010', 'PRONTO(1L AND 3L)', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Sales', '100', '12000', '0', '12000', 'ACTIVE', 'Consumer Price', '4-RAC9992-1-Apr-2014'),
(7, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '3-RS-RAC9992', '2014-04-01', 'Customer Ledger', 'RAC004', 'ALTRO 2(15L-50L)', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Sales', '10', '30000', '0', '30000', 'ACTIVE', 'Consumer Price', '5-RAC9992-1-Apr-2014'),
(8, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '3-RS-RAC9992', '2014-04-01', 'Customer Ledger', 'RAC010', 'PRONTO(1L AND 3L)', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Sales', '100', '12000', '0', '12000', 'ACTIVE', 'Consumer Price', '5-RAC9992-1-Apr-2014'),
(9, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '3-RS-RAC9992', '2014-04-01', 'Customer Ledger', 'RAC006', 'CDR', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Sales', '10', '3000', '0', '3000', 'ACTIVE', 'Consumer Price', '5-RAC9992-1-Apr-2014'),
(10, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '3-RS-RAC9992', '2014-04-01', 'Customer Ledger', 'RAC002', 'ETERNO 2(10L-35L)', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Sales', '25', '18750', '0', '18750', 'ACTIVE', 'Consumer Price', '5-RAC9992-1-Apr-2014'),
(11, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '4-RS-RAC9992', '2014-04-01', 'Customer Ledger', 'RAC004', 'ALTRO 2(15L-50L)', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Sales', '10', '30000', '0', '30000', 'ACTIVE', 'Consumer Price', '7-RAC9992-1-Apr-2014'),
(12, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '4-RS-RAC9992', '2014-04-01', 'Customer Ledger', 'RAC006', 'CDR', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Sales', '10', '4500', '0', '4500', 'ACTIVE', 'Consumer Price', '7-RAC9992-1-Apr-2014'),
(13, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '4-RS-RAC9992', '2014-04-01', 'Customer Ledger', 'RAC007', 'PRONTO(6L)', 'PRODUCT GROUP SAMPLE 1', 'PS SAMPLE 1', 'PT SAMPLE 1', 'Regular Sales', '30', '15000', '0', '15000', 'ACTIVE', 'Consumer Price', '7-RAC9992-1-Apr-2014'),
(14, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '4-RS-RAC9992', '2014-04-01', 'Customer Ledger', 'RAC012', 'OMEGA MAX 8', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Sales', '25', '15000', '0', '15000', 'ACTIVE', 'Consumer Price', '7-RAC9992-1-Apr-2014'),
(15, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '5-RS-RAC9992', '2014-04-01', 'Customer Ledger', 'RAC004', 'ALTRO 2(15L-50L)', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Sales', '10', '30000', '0', '30000', 'ACTIVE', 'Consumer Price', '10-RAC9992-1-Apr-2014'),
(16, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '5-RS-RAC9992', '2014-04-01', 'Customer Ledger', 'RAC006', 'CDR', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Sales', '10', '4500', '0', '4500', 'ACTIVE', 'Consumer Price', '10-RAC9992-1-Apr-2014'),
(17, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '5-RS-RAC9992', '2014-04-01', 'Customer Ledger', 'RAC007', 'PRONTO(6L)', 'PRODUCT GROUP SAMPLE 1', 'PS SAMPLE 1', 'PT SAMPLE 1', 'Regular Sales', '30', '15000', '0', '15000', 'ACTIVE', 'Consumer Price', '10-RAC9992-1-Apr-2014'),
(18, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '5-RS-RAC9992', '2014-04-01', 'Customer Ledger', 'RAC012', 'OMEGA MAX 8', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Sales', '25', '15000', '0', '15000', 'ACTIVE', 'Consumer Price', '10-RAC9992-1-Apr-2014'),
(19, 'Chennai', 'Chennai', 'RAC9992', 'SARAVANA STORES', '5-RS-RAC9992', '2014-04-01', 'Customer Ledger', 'RAC015', 'SOLAR COMMERCIAL', 'PRODUCT GROUP SAMPLE 1', 'PS SAMPLE 1', 'PT SAMPLE 1', 'Regular Sales', '10', '5500', '0', '5500', 'ACTIVE', 'Consumer Price', '10-RAC9992-1-Apr-2014'),
(20, 'Chennai', 'Chennai', 'RAC9991', 'GEORGE ENTERPRISES', '2-RS-RAC9991', '2014-04-01', 'Customer Ledger', 'RAC014', 'ALPHA', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Sales', '50', '9500', '0', '9500', 'ACTIVE', 'Consumer Price', '3-RAC9991-1-Apr-2014'),
(21, 'Chennai', 'Coimbatore', '6351019', 'ABC AGENCIES', '1/14-15-RS-6351019', '2015-02-10', 'Customer', 'PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', 'PRODUCT GROUP SAMPLE 1', 'PS SAMPLE 1', 'PT SAMPLE 1', 'Regular Sales', '10', '29010', '0', '29010', 'ACTIVE', 'Consumer Price', '9-6351019-10-Feb-2015'),
(22, 'Chennai', 'Coimbatore', '6351019', 'ABC AGENCIES', 'RS/2/14-15-RS-6351019', '2015-03-26', 'Customer', 'PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Regular Sales', '10', '44010', '2200.5', '46210.5', 'ACTIVE', 'Retailer', '13-6351019-26-Mar-2015'),
(23, 'Chennai', 'Coimbatore', '6351019', 'ABC AGENCIES', 'ScrS/1/14-15-SS-6351019', '2015-03-26', 'Customer', 'PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Scrap Sales', '10', '29010', '0', '29010', 'ACTIVE', 'Retailer', '14-6351019-26-Mar-2015'),
(24, 'Chennai', 'Coimbatore', '6351019', 'ABC AGENCIES', '2/14-15-RS-6351019', '2015-03-26', 'Customer', 'PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', 'PRODUCT GROUP SAMPLE 1', 'PS SAMPLE 1', 'PT SAMPLE 1', 'Regular Sales', '10', '20980', '0', '20980', 'ACTIVE', 'Institutional', '18-6351019-26-Mar-2015'),
(25, 'Chennai', 'Coimbatore', '6351019', 'ABC AGENCIES', '3/14-15-RS-6351019', '2015-03-27', 'Customer', 'PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', 'PRODUCT GROUP SAMPLE 1', 'PS SAMPLE 1', 'PT SAMPLE 1', 'Regular Sales', '10', '44010', '2200.5', '46210.5', 'ACTIVE', '', '24-6351019-27-Mar-2015');

-- --------------------------------------------------------

--
-- Table structure for table `r_salesreturn`
--

CREATE TABLE IF NOT EXISTS `r_salesreturn` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `regionname` varchar(50) DEFAULT NULL,
  `branchname` varchar(50) DEFAULT NULL,
  `franchisecode` varchar(50) DEFAULT NULL,
  `franchisename` varchar(50) DEFAULT NULL,
  `salesRetno` varchar(50) DEFAULT NULL,
  `salesRetdates` date DEFAULT NULL,
  `retailername` varchar(100) DEFAULT NULL,
  `productcode` varchar(50) DEFAULT NULL,
  `productdes` varchar(100) DEFAULT NULL,
  `pgroupname` varchar(50) DEFAULT NULL,
  `psegmentname` varchar(50) DEFAULT NULL,
  `ptypename` varchar(50) DEFAULT NULL,
  `VoucherType` varchar(50) DEFAULT NULL,
  `quantity` varchar(50) DEFAULT NULL,
  `amount` varchar(50) DEFAULT NULL,
  `TaxAmount` varchar(50) DEFAULT NULL,
  `grossamt` varchar(50) DEFAULT NULL,
  `unique_id` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_2` (`salesRetdates`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `r_salesreturn`
--

INSERT INTO `r_salesreturn` (`id`, `regionname`, `branchname`, `franchisecode`, `franchisename`, `salesRetno`, `salesRetdates`, `retailername`, `productcode`, `productdes`, `pgroupname`, `psegmentname`, `ptypename`, `VoucherType`, `quantity`, `amount`, `TaxAmount`, `grossamt`, `unique_id`) VALUES
(1, 'Chennai', 'Coimbatore', '6351019', 'ABC AGENCIES', 'SR/1/14-15-SR-6351019', '2015-03-26', 'Customer', 'PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', 'PRODUCT GROUP SAMPLE 2', 'PS SAMPLE 2', 'PT SAMPLE 2', 'Sales Return', '2', '4196', '0', '4196', '19-6351019-26-Mar-2015'),
(2, 'Chennai', 'Coimbatore', '6351019', 'ABC AGENCIES', 'SR/2/14-15-SR-6351019', '2015-03-27', 'Dhamu', 'PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', 'PRODUCT GROUP SAMPLE 1', 'PS SAMPLE 1', 'PT SAMPLE 1', 'Sales Return', '1', '4401', '220.05', '4621.05', '25-6351019-27-Mar-2015');

-- --------------------------------------------------------

--
-- Table structure for table `salesbatteryreturn`
--

CREATE TABLE IF NOT EXISTS `salesbatteryreturn` (
  `SalesretNo` varchar(50) DEFAULT NULL,
  `Productcode` varchar(50) DEFAULT NULL,
  `Batteryno` varchar(50) DEFAULT NULL,
  `masterid` varchar(45) NOT NULL,
  KEY `index_1` (`masterid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `salesbatteryreturn`
--

INSERT INTO `salesbatteryreturn` (`SalesretNo`, `Productcode`, `Batteryno`, `masterid`) VALUES
('SR/1/14-15-SR-6351019', 'PRODUCT40', 'Primary Batch', '19-6351019-26-Mar-2015'),
('SR/2/14-15-SR-6351019', 'PRODUCT30', 'Primary Batch', '25-6351019-27-Mar-2015');

-- --------------------------------------------------------

--
-- Table structure for table `salesledgerreturn`
--

CREATE TABLE IF NOT EXISTS `salesledgerreturn` (
  `SalesRetNo` varchar(100) DEFAULT NULL,
  `salesRetdate` date DEFAULT NULL,
  `taxledger` varchar(100) DEFAULT NULL,
  `taxamount` double DEFAULT NULL,
  `franchisecode` varchar(100) DEFAULT NULL,
  `voucherstatus` varchar(30) DEFAULT NULL,
  `masterid` varchar(45) NOT NULL,
  `ledgertaxvalue` double NOT NULL DEFAULT '0',
  KEY `index_1` (`masterid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `salesledgerreturn`
--

INSERT INTO `salesledgerreturn` (`SalesRetNo`, `salesRetdate`, `taxledger`, `taxamount`, `franchisecode`, `voucherstatus`, `masterid`, `ledgertaxvalue`) VALUES
('SR/2/14-15-SR-6351019', '2015-03-27', 'Output VAT@5%', 220.05, '6351019', 'ACTIVE', '25-6351019-27-Mar-2015', 5);

-- --------------------------------------------------------

--
-- Table structure for table `salesreturn`
--

CREATE TABLE IF NOT EXISTS `salesreturn` (
  `salesRetno` varchar(50) NOT NULL DEFAULT '',
  `salesRetdate` date DEFAULT NULL,
  `retailername` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `phoneno` int(50) DEFAULT NULL,
  `narration` varchar(100) DEFAULT NULL,
  `Rettotalamount` int(20) DEFAULT NULL,
  `franchisecode` varchar(50) DEFAULT NULL,
  `VoucherType` varchar(50) DEFAULT NULL,
  `schemename` varchar(50) DEFAULT NULL,
  `voucherstatus` varchar(50) DEFAULT NULL,
  `masterid` varchar(45) NOT NULL,
  PRIMARY KEY (`masterid`),
  UNIQUE KEY `masterid` (`masterid`),
  KEY `salesRetno` (`salesRetno`),
  KEY `index_4` (`salesRetdate`,`franchisecode`,`VoucherType`,`voucherstatus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `salesreturn`
--

INSERT INTO `salesreturn` (`salesRetno`, `salesRetdate`, `retailername`, `address`, `city`, `phoneno`, `narration`, `Rettotalamount`, `franchisecode`, `VoucherType`, `schemename`, `voucherstatus`, `masterid`) VALUES
('SR/1/14-15-SR-6351019', '2015-03-26', 'Customer', NULL, NULL, NULL, '', 4196, '6351019', 'Sales Return', '', 'ACTIVE', '19-6351019-26-Mar-2015'),
('SR/2/14-15-SR-6351019', '2015-03-27', 'Dhamu', NULL, NULL, NULL, '', 4621, '6351019', 'Sales Return', '', 'ACTIVE', '25-6351019-27-Mar-2015');

-- --------------------------------------------------------

--
-- Table structure for table `salesreturnitem`
--

CREATE TABLE IF NOT EXISTS `salesreturnitem` (
  `saleRetsno` varchar(50) DEFAULT NULL,
  `salesRetdate` date DEFAULT NULL,
  `productcode` varchar(50) DEFAULT NULL,
  `productdes` varchar(100) DEFAULT NULL,
  `quantity` varchar(50) DEFAULT NULL,
  `taxvalue` double DEFAULT '0',
  `rate` int(50) DEFAULT NULL,
  `amount` int(60) DEFAULT NULL,
  `franchisecode` varchar(50) DEFAULT NULL,
  `voucherstatus` varchar(30) DEFAULT NULL,
  `masterid` varchar(45) NOT NULL,
  KEY `index_1` (`productcode`,`franchisecode`,`masterid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `salesreturnitem`
--

INSERT INTO `salesreturnitem` (`saleRetsno`, `salesRetdate`, `productcode`, `productdes`, `quantity`, `taxvalue`, `rate`, `amount`, `franchisecode`, `voucherstatus`, `masterid`) VALUES
('SR/1/14-15-SR-6351019', '2015-03-26', 'PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', '2', 0, 2098, 4196, '6351019', 'ACTIVE', '19-6351019-26-Mar-2015'),
('SR/2/14-15-SR-6351019', '2015-03-27', 'PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', '1', 220.05, 4401, 4401, '6351019', 'ACTIVE', '25-6351019-27-Mar-2015');

--
-- Triggers `salesreturnitem`
--
DROP TRIGGER IF EXISTS `after_salesreturn_insert`;
DELIMITER //
CREATE TRIGGER `after_salesreturn_insert` AFTER INSERT ON `salesreturnitem`
 FOR EACH ROW BEGIN
		DECLARE Region_Name VARCHAR(50);
		DECLARE branch_name VARCHAR(50);
		DECLARE Franchise_name VARCHAR(50);
		DECLARE p_description VARCHAR(100);
		DECLARE p_typename VARCHAR(50);
		DECLARE p_segmentname VARCHAR(50);
		DECLARE p_groupname VARCHAR(50);		
		DECLARE retailer_name VARCHAR(50);
		DECLARE Voucher_Type VARCHAR(50);
		DECLARE gross_amt VARCHAR(50);

		SELECT retailername,VoucherType
		INTO @retailer_name,@Voucher_Type
		FROM salesreturn
		WHERE salesreturn.masterid= NEW.masterid;
		
		SELECT branchname,RegionName,Franchisename 
		INTO @branch_name,@Region_Name,@Franchise_name 
		FROM view_rbrs
		WHERE view_rbrs.Franchisecode = NEW.franchisecode;

		SELECT pdescription,ptypename,psegmentname,pgroupname 
		INTO @p_description,@p_typename,@p_segmentname,@p_groupname 
		FROM view_productdtetails
		WHERE view_productdtetails.pcode = NEW.productcode;
		
		SET @gross_amt = NEW.amount + NEW.taxvalue;
		
		INSERT INTO r_salesreturn 
		(regionname,branchname,franchisecode,franchisename,salesRetno,salesRetdates,retailername,productcode,productdes,pgroupname,psegmentname,ptypename,VoucherType,quantity,amount,TaxAmount,grossamt,unique_id)
		VALUES
		(@Region_Name,@branch_name,NEW.franchisecode,@Franchise_name,NEW.saleRetsno,NEW.salesRetdate,@retailer_name,NEW.productcode,@p_description,@p_groupname,@p_segmentname,@p_typename,@Voucher_Type,NEW.quantity,NEW.amount,NEW.taxvalue,@gross_amt,NEW.masterid);
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `after_salesreturn_update`;
DELIMITER //
CREATE TRIGGER `after_salesreturn_update` AFTER UPDATE ON `salesreturnitem`
 FOR EACH ROW BEGIN
		DELETE FROM r_salesreturn WHERE unique_id = NEW.masterid;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `after_salesreturn_delete`;
DELIMITER //
CREATE TRIGGER `after_salesreturn_delete` AFTER DELETE ON `salesreturnitem`
 FOR EACH ROW BEGIN
		DELETE FROM r_salesreturn WHERE unique_id = OLD.masterid;	
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `sapmle`
--
CREATE TABLE IF NOT EXISTS `sapmle` (
`ProductDescription` varchar(100)
,`MapProductCode` varchar(50)
,`effectivedate` date
);
-- --------------------------------------------------------

--
-- Table structure for table `schememaster`
--

CREATE TABLE IF NOT EXISTS `schememaster` (
  `schemecode` varchar(50) NOT NULL DEFAULT '',
  `schemename` varchar(50) DEFAULT NULL,
  `schemestatus` varchar(15) DEFAULT NULL,
  `effectivedate` date DEFAULT NULL,
  `schemetype` varchar(15) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `m_date` datetime DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`schemecode`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `schememasterupload`
--

CREATE TABLE IF NOT EXISTS `schememasterupload` (
  `Franchiseecode` varchar(30) DEFAULT NULL,
  `Masters` varchar(50) DEFAULT NULL,
  `Code` varchar(100) DEFAULT NULL,
  `Status` varchar(30) DEFAULT NULL,
  `InsertDate` date DEFAULT NULL,
  `Deliverydae` date DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `serialnumbermaster`
--

CREATE TABLE IF NOT EXISTS `serialnumbermaster` (
  `Category` varchar(30) NOT NULL,
  `TertiarySalesEntryDate` date NOT NULL,
  `BatterySlNo` varchar(50) NOT NULL,
  `DateofSale` date NOT NULL,
  `salesinvoiceno` varchar(100) NOT NULL,
  `oldProductCode` varchar(50) NOT NULL,
  `ManufacturingDate` date NOT NULL,
  `ProductCode` varchar(50) NOT NULL,
  `CustomerName` varchar(50) NOT NULL,
  `CustomerAddress` varchar(230) DEFAULT NULL,
  `City` varchar(50) NOT NULL,
  `CustomerPhoneNo` varchar(50) NOT NULL,
  `RetailerName` varchar(50) DEFAULT NULL,
  `FranchiseeName` varchar(50) NOT NULL,
  `VehicleorInverterModel` varchar(50) NOT NULL,
  `VehicleorInverterMake` varchar(50) NOT NULL,
  `VehicleSegment` varchar(50) NOT NULL,
  `Enginetype` varchar(50) DEFAULT NULL,
  `VehicleNo` varchar(50) DEFAULT NULL,
  `batterystatus` varchar(40) NOT NULL,
  `oldbatteryno` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Active',
  `oemname` varchar(30) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) DEFAULT NULL,
  `m_date` datetime NOT NULL,
  `voucherstatus` varchar(30) DEFAULT 'ACTIVE',
  `masterid` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`BatterySlNo`),
  UNIQUE KEY `masterid` (`masterid`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `servicemaster`
--

CREATE TABLE IF NOT EXISTS `servicemaster` (
  `Productcode` varchar(50) DEFAULT NULL,
  `EffectiveDate` date DEFAULT NULL,
  `CompensationValue` varchar(50) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `m_date` datetime DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  KEY `Id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `servicemasterview`
--
CREATE TABLE IF NOT EXISTS `servicemasterview` (
`ProductCode` varchar(50)
,`ProductDescription` varchar(100)
,`EffectiveDate` date
,`CompensationValue` varchar(50)
);
-- --------------------------------------------------------

--
-- Table structure for table `state`
--

CREATE TABLE IF NOT EXISTS `state` (
  `statecode` varchar(15) NOT NULL DEFAULT '',
  `statename` varchar(50) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `m_date` datetime DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`statecode`),
  KEY `Id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=112 ;

--
-- Dumping data for table `state`
--

INSERT INTO `state` (`statecode`, `statename`, `user_id`, `m_date`, `id`) VALUES
('1', 'Andhra Pradesh', '1000213', '2013-05-17 12:36:34', 72),
('10', 'Karnataka', '', '0000-00-00 00:00:00', 81),
('11', 'Kerala', '', '0000-00-00 00:00:00', 82),
('12', 'Madhya Pradesh', '', '0000-00-00 00:00:00', 83),
('13', 'Maharashtra', '', '0000-00-00 00:00:00', 84),
('14', 'Manipur', '', '0000-00-00 00:00:00', 85),
('15', 'Megalaya', '', '0000-00-00 00:00:00', 86),
('16', 'Mizoram', '', '0000-00-00 00:00:00', 87),
('17', 'Nagaland', '', '0000-00-00 00:00:00', 88),
('18', 'Orissa', '', '0000-00-00 00:00:00', 89),
('19', 'Punjab', '', '0000-00-00 00:00:00', 90),
('2', 'Arunachal Pradesh', '', '0000-00-00 00:00:00', 73),
('20', 'Rajasthan', '', '0000-00-00 00:00:00', 91),
('21', 'Sikkim', '', '0000-00-00 00:00:00', 92),
('22', 'Tamil Nadu', '', '0000-00-00 00:00:00', 93),
('23', 'Tripura', '', '0000-00-00 00:00:00', 94),
('24', 'Uttar Pradesh', '', '0000-00-00 00:00:00', 95),
('25', 'West Bengal', '', '0000-00-00 00:00:00', 96),
('26', 'Andaman and Nico.In.', '1000888', '2013-05-30 15:39:28', 97),
('27', 'Chandigarh', '', '0000-00-00 00:00:00', 98),
('28', 'Dadra and Nagar Hav.', '1000888', '2013-05-30 15:39:10', 99),
('29', 'Daman and Diu', '1000888', '2013-05-30 15:39:18', 100),
('3', 'Assam', '', '0000-00-00 00:00:00', 74),
('30', 'Delhi', '', '0000-00-00 00:00:00', 101),
('31', 'Lakshadweep', '', '0000-00-00 00:00:00', 102),
('32', 'Pondicherry', '', '0000-00-00 00:00:00', 103),
('33', 'Chhaattisgarh', '', '0000-00-00 00:00:00', 104),
('34', 'Jharkhand', '1000888', '2013-07-06 13:28:18', 111),
('35', 'Uttarakhand', 'admin', '2013-06-03 15:42:45', 106),
('4', 'Bihar', '', '0000-00-00 00:00:00', 75),
('5', 'Goa', '', '0000-00-00 00:00:00', 76),
('6', 'Gujarat', '', '0000-00-00 00:00:00', 77),
('7', 'Haryana', '', '0000-00-00 00:00:00', 78),
('8', 'Himachal Pradesh', '', '0000-00-00 00:00:00', 79),
('9', 'Jammu and Kashmir', '', '0000-00-00 00:00:00', 80);

-- --------------------------------------------------------

--
-- Table structure for table `stockledgerfinal`
--

CREATE TABLE IF NOT EXISTS `stockledgerfinal` (
  `productcode` varchar(50) DEFAULT NULL,
  `productdes` varchar(100) DEFAULT NULL,
  `opstock` double DEFAULT NULL,
  `IsseudQty` double DEFAULT NULL,
  `ReceivedQty` decimal(65,0) DEFAULT NULL,
  `StockonHand` double DEFAULT NULL,
  `Rate` decimal(13,2) DEFAULT NULL,
  `stockvalue` double(19,2) DEFAULT NULL,
  `franchisecode` varchar(50) DEFAULT NULL,
  `ptypecode` varchar(50) DEFAULT NULL,
  `ptypename` varchar(50) DEFAULT NULL,
  `psegmentcode` varchar(50) DEFAULT NULL,
  `psegmentname` varchar(50) DEFAULT NULL,
  `pgroupcode` varchar(50) DEFAULT NULL,
  `pgroupname` varchar(50) DEFAULT NULL,
  `Franchisename` varchar(50) DEFAULT NULL,
  `Branch` varchar(50) DEFAULT NULL,
  `branchname` varchar(50) DEFAULT NULL,
  `Region` varchar(50) DEFAULT NULL,
  `RegionName` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stockledgerreport`
--

CREATE TABLE IF NOT EXISTS `stockledgerreport` (
  `franchiseecode` varchar(50) NOT NULL,
  `opdate` date NOT NULL,
  `productcode` varchar(50) NOT NULL,
  `productdescription` varchar(100) NOT NULL,
  `openstock` int(25) NOT NULL,
  `rate` double NOT NULL DEFAULT '0',
  `stockvalue` double NOT NULL DEFAULT '0',
  `mdate` date NOT NULL,
  KEY `index_1` (`franchiseecode`,`productcode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stockledgerreport`
--

INSERT INTO `stockledgerreport` (`franchiseecode`, `opdate`, `productcode`, `productdescription`, `openstock`, `rate`, `stockvalue`, `mdate`) VALUES
('6351019', '2014-04-01', 'PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', 100, 1000, 100000, '2015-02-10'),
('6351019', '2014-04-01', 'PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', 100, 1000, 100000, '2015-02-10'),
('RAC9992', '2014-04-01', 'RAC014', 'ALPHA', 0, 0, 0, '2015-03-04'),
('RAC9992', '2014-04-01', 'RAC004', 'ALTRO 2(15L-50L)', 50, 0, 0, '2015-03-04'),
('RAC9992', '2014-04-01', 'RAC006', 'CDR', 50, 0, 0, '2015-03-04'),
('RAC9992', '2014-04-01', 'RAC002', 'ETERNO 2(10L-35L)', 100, 0, 0, '2015-03-04'),
('RAC9992', '2014-04-01', 'RAC001', 'ETERNO DG(15L AND 25L)', 100, 0, 0, '2015-03-04'),
('RAC9992', '2014-04-01', 'PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', 0, 0, 0, '2015-03-04'),
('RAC9992', '2014-04-01', 'RAC016', 'HEAT PUMP', 0, 0, 0, '2015-03-04'),
('RAC9992', '2014-04-01', 'RAC011', 'NATURAL FLUE', 0, 0, 0, '2015-03-04'),
('RAC9992', '2014-04-01', 'RAC013', 'OMEGA', 0, 0, 0, '2015-03-04'),
('RAC9992', '2014-04-01', 'RAC012', 'OMEGA MAX 8', 100, 0, 0, '2015-03-04'),
('RAC9992', '2014-04-01', 'RAC009', 'PLATINUM(150L-300L)', 0, 0, 0, '2015-03-04'),
('RAC9992', '2014-04-01', 'RAC008', 'PLATINUM(50L-100L)', 30, 0, 0, '2015-03-04'),
('RAC9992', '2014-04-01', 'RAC010', 'PRONTO(1L AND 3L)', 40, 0, 0, '2015-03-04'),
('RAC9992', '2014-04-01', 'RAC007', 'PRONTO(6L)', 50, 0, 0, '2015-03-04'),
('RAC9992', '2014-04-01', 'RAC015', 'SOLAR COMMERCIAL', 100, 0, 0, '2015-03-04'),
('RAC9992', '2014-04-01', 'PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', 0, 0, 0, '2015-03-04'),
('RAC9991', '2014-04-01', 'RAC014', 'ALPHA', 150, 20, 3000, '2015-03-05'),
('RAC9991', '2014-04-01', 'RAC004', 'ALTRO 2(15L-50L)', 0, 0, 0, '2015-03-05'),
('RAC9991', '2014-04-01', 'RAC006', 'CDR', 0, 0, 0, '2015-03-05'),
('RAC9991', '2014-04-01', 'RAC002', 'ETERNO 2(10L-35L)', 150, 20, 3000, '2015-03-05'),
('RAC9991', '2014-04-01', 'RAC001', 'ETERNO DG(15L AND 25L)', 200, 10, 2000, '2015-03-05'),
('RAC9991', '2014-04-01', 'PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', 0, 0, 0, '2015-03-05'),
('RAC9991', '2014-04-01', 'RAC016', 'HEAT PUMP', 0, 0, 0, '2015-03-05'),
('RAC9991', '2014-04-01', 'RAC011', 'NATURAL FLUE', 0, 0, 0, '2015-03-05'),
('RAC9991', '2014-04-01', 'RAC013', 'OMEGA', 0, 0, 0, '2015-03-05'),
('RAC9991', '2014-04-01', 'RAC012', 'OMEGA MAX 8', 300, 10, 3000, '2015-03-05'),
('RAC9991', '2014-04-01', 'RAC009', 'PLATINUM(150L-300L)', 0, 0, 0, '2015-03-05'),
('RAC9991', '2014-04-01', 'RAC008', 'PLATINUM(50L-100L)', 0, 0, 0, '2015-03-05'),
('RAC9991', '2014-04-01', 'RAC010', 'PRONTO(1L AND 3L)', 0, 0, 0, '2015-03-05'),
('RAC9991', '2014-04-01', 'RAC007', 'PRONTO(6L)', 0, 0, 0, '2015-03-05'),
('RAC9991', '2014-04-01', 'RAC015', 'SOLAR COMMERCIAL', 0, 0, 0, '2015-03-05'),
('RAC9991', '2014-04-01', 'PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', 0, 0, 0, '2015-03-05'),
('1432434', '2014-04-01', 'RAC014', 'ALPHA', 500, 100, 50000, '2015-03-16'),
('1432434', '2014-04-01', 'RAC004', 'ALTRO 2(15L-50L)', 0, 0, 0, '2015-03-16'),
('1432434', '2014-04-01', 'RAC006', 'CDR', 200, 10, 2000, '2015-03-16'),
('1432434', '2014-04-01', 'RAC002', 'ETERNO 2(10L-35L)', 0, 0, 0, '2015-03-16'),
('1432434', '2014-04-01', 'RAC001', 'ETERNO DG(15L AND 25L)', 0, 0, 0, '2015-03-16'),
('1432434', '2014-04-01', 'PRODUCT40', 'FOURTH ZERO SAMPLE PRODUCT', 0, 0, 0, '2015-03-16'),
('1432434', '2014-04-01', 'RAC016', 'HEAT PUMP', 0, 0, 0, '2015-03-16'),
('1432434', '2014-04-01', 'RAC011', 'NATURAL FLUE', 0, 0, 0, '2015-03-16'),
('1432434', '2014-04-01', 'RAC013', 'OMEGA', 0, 0, 0, '2015-03-16'),
('1432434', '2014-04-01', 'RAC012', 'OMEGA MAX 8', 0, 0, 0, '2015-03-16'),
('1432434', '2014-04-01', 'RAC009', 'PLATINUM(150L-300L)', 0, 0, 0, '2015-03-16'),
('1432434', '2014-04-01', 'RAC008', 'PLATINUM(50L-100L)', 0, 0, 0, '2015-03-16'),
('1432434', '2014-04-01', 'RAC010', 'PRONTO(1L AND 3L)', 0, 0, 0, '2015-03-16'),
('1432434', '2014-04-01', 'RAC007', 'PRONTO(6L)', 0, 0, 0, '2015-03-16'),
('1432434', '2014-04-01', 'RAC015', 'SOLAR COMMERCIAL', 0, 0, 0, '2015-03-16'),
('1432434', '2014-04-01', 'PRODUCT30', 'THIRD ZERO SAMPLE PRODUCT', 0, 0, 0, '2015-03-16');

-- --------------------------------------------------------

--
-- Table structure for table `stock_day`
--

CREATE TABLE IF NOT EXISTS `stock_day` (
  `franchiseecode` varchar(50) NOT NULL,
  `productcode` varchar(50) NOT NULL,
  `stockdate` date NOT NULL,
  `receipt` int(10) NOT NULL DEFAULT '0',
  `issue` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`franchiseecode`,`productcode`,`stockdate`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stock_day`
--

INSERT INTO `stock_day` (`franchiseecode`, `productcode`, `stockdate`, `receipt`, `issue`) VALUES
('1432434', 'RAC014', '2014-04-01', 10, 0),
('6351019', 'PRODUCT30', '2015-02-10', 20, 20),
('6351019', 'PRODUCT30', '2015-03-26', 100, 0),
('6351019', 'PRODUCT40', '2015-02-10', 15, 10),
('6351019', 'PRODUCT40', '2015-03-26', 100, 10),
('RAC9991', 'RAC002', '2014-04-01', 30, 100),
('RAC9991', 'RAC014', '2014-04-01', 100, 50),
('RAC9992', 'RAC001', '2014-04-01', 170, 0),
('RAC9992', 'RAC002', '2014-04-01', 0, 25),
('RAC9992', 'RAC004', '2014-04-01', 0, 50),
('RAC9992', 'RAC006', '2014-04-01', 10, 30),
('RAC9992', 'RAC007', '2014-04-01', 20, 60),
('RAC9992', 'RAC010', '2014-04-01', 0, 200),
('RAC9992', 'RAC012', '2014-04-01', 496, 50),
('RAC9992', 'RAC014', '2014-04-01', 100, 0),
('RAC9992', 'RAC015', '2014-04-01', 0, 10);

-- --------------------------------------------------------

--
-- Table structure for table `stock_month`
--

CREATE TABLE IF NOT EXISTS `stock_month` (
  `franchiseecode` varchar(50) NOT NULL,
  `productcode` varchar(50) NOT NULL,
  `stockdate` varchar(20) NOT NULL,
  `openstock` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`franchiseecode`,`productcode`,`stockdate`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stock_month`
--

INSERT INTO `stock_month` (`franchiseecode`, `productcode`, `stockdate`, `openstock`) VALUES
('1432434', 'RAC014', '2014-05', 10),
('1432434', 'RAC014', '2014-06', 10),
('1432434', 'RAC014', '2014-07', 10),
('1432434', 'RAC014', '2014-08', 10),
('1432434', 'RAC014', '2014-09', 10),
('1432434', 'RAC014', '2014-10', 10),
('1432434', 'RAC014', '2014-11', 10),
('1432434', 'RAC014', '2014-12', 10),
('1432434', 'RAC014', '2015-01', 10),
('1432434', 'RAC014', '2015-02', 10),
('1432434', 'RAC014', '2015-03', 10),
('6351019', 'PRODUCT30', '2015-03', 0),
('6351019', 'PRODUCT40', '2015-03', 5),
('RAC9991', 'RAC002', '2014-05', -70),
('RAC9991', 'RAC002', '2014-06', -70),
('RAC9991', 'RAC002', '2014-07', -70),
('RAC9991', 'RAC002', '2014-08', -70),
('RAC9991', 'RAC002', '2014-09', -70),
('RAC9991', 'RAC002', '2014-10', -70),
('RAC9991', 'RAC002', '2014-11', -70),
('RAC9991', 'RAC002', '2014-12', -70),
('RAC9991', 'RAC002', '2015-01', -70),
('RAC9991', 'RAC002', '2015-02', -70),
('RAC9991', 'RAC002', '2015-03', -70),
('RAC9991', 'RAC014', '2014-05', 50),
('RAC9991', 'RAC014', '2014-06', 50),
('RAC9991', 'RAC014', '2014-07', 50),
('RAC9991', 'RAC014', '2014-08', 50),
('RAC9991', 'RAC014', '2014-09', 50),
('RAC9991', 'RAC014', '2014-10', 50),
('RAC9991', 'RAC014', '2014-11', 50),
('RAC9991', 'RAC014', '2014-12', 50),
('RAC9991', 'RAC014', '2015-01', 50),
('RAC9991', 'RAC014', '2015-02', 50),
('RAC9991', 'RAC014', '2015-03', 50),
('RAC9992', 'RAC001', '2014-05', 170),
('RAC9992', 'RAC001', '2014-06', 170),
('RAC9992', 'RAC001', '2014-07', 170),
('RAC9992', 'RAC001', '2014-08', 170),
('RAC9992', 'RAC001', '2014-09', 170),
('RAC9992', 'RAC001', '2014-10', 170),
('RAC9992', 'RAC001', '2014-11', 170),
('RAC9992', 'RAC001', '2014-12', 170),
('RAC9992', 'RAC001', '2015-01', 170),
('RAC9992', 'RAC001', '2015-02', 170),
('RAC9992', 'RAC001', '2015-03', 170),
('RAC9992', 'RAC002', '2014-05', -25),
('RAC9992', 'RAC002', '2014-06', -25),
('RAC9992', 'RAC002', '2014-07', -25),
('RAC9992', 'RAC002', '2014-08', -25),
('RAC9992', 'RAC002', '2014-09', -25),
('RAC9992', 'RAC002', '2014-10', -25),
('RAC9992', 'RAC002', '2014-11', -25),
('RAC9992', 'RAC002', '2014-12', -25),
('RAC9992', 'RAC002', '2015-01', -25),
('RAC9992', 'RAC002', '2015-02', -25),
('RAC9992', 'RAC002', '2015-03', -25),
('RAC9992', 'RAC004', '2014-05', -50),
('RAC9992', 'RAC004', '2014-06', -50),
('RAC9992', 'RAC004', '2014-07', -50),
('RAC9992', 'RAC004', '2014-08', -50),
('RAC9992', 'RAC004', '2014-09', -50),
('RAC9992', 'RAC004', '2014-10', -50),
('RAC9992', 'RAC004', '2014-11', -50),
('RAC9992', 'RAC004', '2014-12', -50),
('RAC9992', 'RAC004', '2015-01', -50),
('RAC9992', 'RAC004', '2015-02', -50),
('RAC9992', 'RAC004', '2015-03', -50),
('RAC9992', 'RAC006', '2014-05', -20),
('RAC9992', 'RAC006', '2014-06', -20),
('RAC9992', 'RAC006', '2014-07', -20),
('RAC9992', 'RAC006', '2014-08', -20),
('RAC9992', 'RAC006', '2014-09', -20),
('RAC9992', 'RAC006', '2014-10', -20),
('RAC9992', 'RAC006', '2014-11', -20),
('RAC9992', 'RAC006', '2014-12', -20),
('RAC9992', 'RAC006', '2015-01', -20),
('RAC9992', 'RAC006', '2015-02', -20),
('RAC9992', 'RAC006', '2015-03', -20),
('RAC9992', 'RAC007', '2014-05', -40),
('RAC9992', 'RAC007', '2014-06', -40),
('RAC9992', 'RAC007', '2014-07', -40),
('RAC9992', 'RAC007', '2014-08', -40),
('RAC9992', 'RAC007', '2014-09', -40),
('RAC9992', 'RAC007', '2014-10', -40),
('RAC9992', 'RAC007', '2014-11', -40),
('RAC9992', 'RAC007', '2014-12', -40),
('RAC9992', 'RAC007', '2015-01', -40),
('RAC9992', 'RAC007', '2015-02', -40),
('RAC9992', 'RAC007', '2015-03', -40),
('RAC9992', 'RAC010', '2014-05', -200),
('RAC9992', 'RAC010', '2014-06', -200),
('RAC9992', 'RAC010', '2014-07', -200),
('RAC9992', 'RAC010', '2014-08', -200),
('RAC9992', 'RAC010', '2014-09', -200),
('RAC9992', 'RAC010', '2014-10', -200),
('RAC9992', 'RAC010', '2014-11', -200),
('RAC9992', 'RAC010', '2014-12', -200),
('RAC9992', 'RAC010', '2015-01', -200),
('RAC9992', 'RAC010', '2015-02', -200),
('RAC9992', 'RAC010', '2015-03', -200),
('RAC9992', 'RAC012', '2014-05', 446),
('RAC9992', 'RAC012', '2014-06', 446),
('RAC9992', 'RAC012', '2014-07', 446),
('RAC9992', 'RAC012', '2014-08', 446),
('RAC9992', 'RAC012', '2014-09', 446),
('RAC9992', 'RAC012', '2014-10', 446),
('RAC9992', 'RAC012', '2014-11', 446),
('RAC9992', 'RAC012', '2014-12', 446),
('RAC9992', 'RAC012', '2015-01', 446),
('RAC9992', 'RAC012', '2015-02', 446),
('RAC9992', 'RAC012', '2015-03', 446),
('RAC9992', 'RAC014', '2014-05', 100),
('RAC9992', 'RAC014', '2014-06', 100),
('RAC9992', 'RAC014', '2014-07', 100),
('RAC9992', 'RAC014', '2014-08', 100),
('RAC9992', 'RAC014', '2014-09', 100),
('RAC9992', 'RAC014', '2014-10', 100),
('RAC9992', 'RAC014', '2014-11', 100),
('RAC9992', 'RAC014', '2014-12', 100),
('RAC9992', 'RAC014', '2015-01', 100),
('RAC9992', 'RAC014', '2015-02', 100),
('RAC9992', 'RAC014', '2015-03', 100),
('RAC9992', 'RAC015', '2014-05', -10),
('RAC9992', 'RAC015', '2014-06', -10),
('RAC9992', 'RAC015', '2014-07', -10),
('RAC9992', 'RAC015', '2014-08', -10),
('RAC9992', 'RAC015', '2014-09', -10),
('RAC9992', 'RAC015', '2014-10', -10),
('RAC9992', 'RAC015', '2014-11', -10),
('RAC9992', 'RAC015', '2014-12', -10),
('RAC9992', 'RAC015', '2015-01', -10),
('RAC9992', 'RAC015', '2015-02', -10),
('RAC9992', 'RAC015', '2015-03', -10);

-- --------------------------------------------------------

--
-- Table structure for table `stock_open`
--

CREATE TABLE IF NOT EXISTS `stock_open` (
  `franchiseecode` varchar(50) NOT NULL,
  `productcode` varchar(50) NOT NULL,
  `stockdate` varchar(20) NOT NULL,
  `openstock` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`franchiseecode`,`productcode`,`stockdate`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `suppliermaster`
--

CREATE TABLE IF NOT EXISTS `suppliermaster` (
  `SupplierCode` varchar(20) NOT NULL DEFAULT '',
  `SupplierName` varchar(50) DEFAULT NULL,
  `Address` varchar(100) DEFAULT NULL,
  `Branch` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `PinCode` varchar(20) DEFAULT NULL,
  `PanItNo` varchar(30) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`SupplierCode`),
  KEY `Id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tempdate`
--

CREATE TABLE IF NOT EXISTS `tempdate` (
  `from` date NOT NULL,
  `to` date NOT NULL,
  `day` int(30) NOT NULL,
  `month` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tempdate`
--

INSERT INTO `tempdate` (`from`, `to`, `day`, `month`) VALUES
('2015-03-01', '2015-04-30', 0, '5');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE IF NOT EXISTS `transactions` (
  `Date` datetime DEFAULT NULL,
  `InvoiceNumber` varchar(50) DEFAULT NULL,
  `Customername` varchar(100) DEFAULT NULL,
  `Productcode` varchar(50) DEFAULT NULL,
  `ProductDescription` longtext,
  `TRIAL_COLUMN6` varchar(50) DEFAULT NULL,
  `TRIAL_COLUMN7` varchar(50) DEFAULT NULL,
  `TRIAL_COLUMN8` varchar(50) DEFAULT NULL,
  `TRIAL_COLUMN9` varchar(50) DEFAULT NULL,
  `TRIAL_COLUMN10` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `uploadstatus`
--

CREATE TABLE IF NOT EXISTS `uploadstatus` (
  `franchisecode` int(30) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  KEY `index_1` (`franchisecode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `uploadstatus`
--

INSERT INTO `uploadstatus` (`franchisecode`, `date`, `status`) VALUES
(6351019, '2015-02-10', 'Delivered'),
(6351019, '2015-02-19', 'Delivered'),
(0, '2015-03-04', 'Delivered'),
(0, '2015-03-04', 'Delivered'),
(0, '2015-03-04', 'Delivered'),
(0, '2015-03-04', 'Delivered'),
(0, '2015-03-04', 'Delivered'),
(0, '2015-03-04', 'Delivered'),
(0, '2015-03-04', 'Delivered'),
(0, '2015-03-04', 'Delivered'),
(0, '2015-03-05', 'Delivered'),
(0, '2015-03-05', 'Delivered'),
(6351019, '2015-03-09', 'Delivered'),
(6351019, '2015-03-09', 'Delivered'),
(1432434, '2015-03-16', 'Delivered'),
(6351019, '2015-03-26', 'Delivered'),
(6351019, '2015-03-26', 'Delivered'),
(6351019, '2015-03-26', 'Delivered'),
(6351019, '2015-03-26', 'Delivered'),
(6351019, '2015-03-26', 'Delivered'),
(6351019, '2015-03-26', 'Delivered'),
(6351019, '2015-03-26', 'Delivered'),
(6351019, '2015-03-26', 'Delivered'),
(6351019, '2015-03-27', 'Delivered'),
(6351019, '2015-03-27', 'Delivered'),
(6351019, '2015-03-27', 'Delivered'),
(6351019, '2015-03-27', 'Delivered'),
(6351019, '2015-03-27', 'Delivered');

-- --------------------------------------------------------

--
-- Table structure for table `usercreation`
--

CREATE TABLE IF NOT EXISTS `usercreation` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `userid` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(128) DEFAULT NULL,
  `empcode` varchar(50) DEFAULT NULL,
  `salt` varchar(128) DEFAULT NULL,
  `timeout` datetime DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `estatus` varchar(10) DEFAULT NULL,
  `count` int(10) DEFAULT NULL,
  PRIMARY KEY (`userid`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

--
-- Dumping data for table `usercreation`
--

INSERT INTO `usercreation` (`id`, `userid`, `password`, `empcode`, `salt`, `timeout`, `status`, `estatus`, `count`) VALUES
(1, 'admin', '869ab816a2e9e4d8689d4308941c0f0a18dfd2fb0945c2febf11b755aa6c577a996e640fb4773326602cc5d9d5e8e555275de34bb4d9cec3eaff0b466de6290d', 'E01', 'dee04d31c7d2c304e88f243646723796fafacf5518468feef6f52ed60ace2204ccbfd50bd417d6a90a000a89eeb582d035bff24bc05549906d58cb81ca5938c1', '2015-04-29 10:23:14', 'OUT', 'OLD', 0),
(34, 'Jayapremnath', '6758e205fe8439cfb8c51350281791d4bb94ace624e93f6499e85a640452521eaceda44b9f98884ace4558a69df228f194431b482cb1acf1bd8d6839239c2c4d', 'a', 'd854257d83e4158cd888e1670c961db32d84b8aa13bdb8f9e4ac3de25c85dfda9dd10d1fed257a71452a761887ab032309381197bdcda1d4c956b132045de04a', '2015-04-01 15:11:09', 'IN', 'NEW', 0),
(33, 'tally', 'eb47bcd271dcdfbde927d15b971293a7df7aa3124b6cec440539ba06a70deb845f7e283668921b80b36b1127217a32bfb2f4a4e891cb5edaa5c221ab73c4f888', 'tally', '99d9b6aae2354753cddca0fff5ca048463910b2560d126ccc7329026d947ad32e7905eee31bbcf0bf33bb5bf417686c6a6b1cdbad8376e145b8318925c64739f', '2015-03-27 16:05:19', 'OUT', 'OLD', 0),
(30, 'tiara', '174aee2bdb802ddfc90159f330c352aeaf88c5f801820a330832a747cde99fae8139a738cc44898d0317af2ee3b4a684737f32ab8633f5430fd8c71dd9f9bc36', 'tiara', 'dee04d31c7d2c304e88f243646723796fafacf5518468feef6f52ed60ace2204ccbfd50bd417d6a90a000a89eeb582d035bff24bc05549906d58cb81ca5938c1', '2015-03-16 18:59:32', 'OUT', 'OLD', 0);

-- --------------------------------------------------------

--
-- Table structure for table `userrights`
--

CREATE TABLE IF NOT EXISTS `userrights` (
  `userid` varchar(10) DEFAULT NULL,
  `screen` varchar(30) DEFAULT NULL,
  `viewrights` varchar(10) DEFAULT NULL,
  `addrights` varchar(10) DEFAULT NULL,
  `editrights` varchar(10) DEFAULT NULL,
  `deleterights` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `userrights`
--

INSERT INTO `userrights` (`userid`, `screen`, `viewrights`, `addrights`, `editrights`, `deleterights`) VALUES
('admin', 'oemmaster', 'Yes', 'Yes', 'Yes', 'Yes'),
('admin', 'ProductGroupmaster', 'Yes', 'Yes', 'Yes', 'Yes'),
('admin', 'Productssegment', 'Yes', 'Yes', 'Yes', 'Yes'),
('admin', 'Productstype', 'Yes', 'Yes', 'Yes', 'Yes'),
('admin', 'productuom', 'Yes', 'Yes', 'Yes', 'Yes'),
('admin', 'proratalogic', 'Yes', 'Yes', 'Yes', 'Yes'),
('admin', 'Productsdetails', 'Yes', 'Yes', 'Yes', 'Yes'),
('admin', 'Productswarranty', 'Yes', 'Yes', 'Yes', 'Yes'),
('admin', 'Productsmapping', 'Yes', 'Yes', 'Yes', 'Yes'),
('admin', 'Service Compensation Master', 'Yes', 'Yes', 'Yes', 'Yes'),
('admin', 'Franchise Master', 'Yes', 'Yes', 'Yes', 'Yes'),
('admin', 'Vehiclemake', 'Yes', 'Yes', 'Yes', 'Yes'),
('admin', 'Vehiclesegment', 'Yes', 'Yes', 'Yes', 'Yes'),
('admin', 'Vehiclemodel', 'Yes', 'Yes', 'Yes', 'Yes'),
('admin', 'Retailer', 'Yes', 'Yes', 'Yes', 'Yes'),
('admin', 'Retailercategory', 'Yes', 'Yes', 'Yes', 'Yes'),
('admin', 'Pricelist', 'Yes', 'Yes', 'Yes', 'Yes'),
('admin', 'PricelistLink', 'Yes', 'Yes', 'Yes', 'Yes'),
('admin', 'scheme', 'Yes', 'Yes', 'Yes', 'Yes'),
('admin', 'Failure Mode', 'Yes', 'Yes', 'Yes', 'Yes'),
('admin', 'Branch Master', 'Yes', 'Yes', 'Yes', 'Yes'),
('admin', 'State Master', 'Yes', 'Yes', 'Yes', 'Yes'),
('admin', 'Region Master', 'Yes', 'Yes', 'Yes', 'Yes'),
('admin', 'Country Master', 'Yes', 'Yes', 'Yes', 'Yes'),
('admin', 'masterimport', 'Yes', 'No', 'Yes', 'No'),
('admin', 'configuration', 'Yes', 'No', 'Yes', 'No'),
('admin', 'User Creation', 'Yes', 'Yes', 'Yes', 'Yes'),
('admin', 'Password change Master', 'Yes', 'No', 'Yes', 'No'),
('admin', 'User rights Master', 'Yes', 'No', 'Yes', 'No'),
('admin', 'SerialNO', 'Yes', 'Yes', 'Yes', 'Yes'),
('admin', 'Supplier Master', 'Yes', 'Yes', 'Yes', 'Yes'),
('admin', 'Employee Master', 'Yes', 'Yes', 'Yes', 'Yes'),
('tiara', 'oemmaster', 'Yes', 'Yes', 'Yes', 'Yes'),
('tiara', 'ProductGroupmaster', 'Yes', 'Yes', 'Yes', 'Yes'),
('tiara', 'Productssegment', 'Yes', 'Yes', 'Yes', 'Yes'),
('tiara', 'Productstype', 'Yes', 'Yes', 'Yes', 'Yes'),
('tiara', 'productuom', 'Yes', 'Yes', 'Yes', 'Yes'),
('tiara', 'proratalogic', 'Yes', 'Yes', 'Yes', 'Yes'),
('tiara', 'Productsdetails', 'Yes', 'Yes', 'Yes', 'Yes'),
('tiara', 'Productswarranty', 'Yes', 'Yes', 'Yes', 'Yes'),
('tiara', 'Productsmapping', 'Yes', 'Yes', 'Yes', 'Yes'),
('tiara', 'Service Compensation Master', 'Yes', 'Yes', 'Yes', 'Yes'),
('tiara', 'Franchise Master', 'Yes', 'Yes', 'Yes', 'Yes'),
('tiara', 'Vehiclemake', 'Yes', 'Yes', 'Yes', 'Yes'),
('tiara', 'Vehiclesegment', 'Yes', 'Yes', 'Yes', 'Yes'),
('tiara', 'Vehiclemodel', 'Yes', 'Yes', 'Yes', 'Yes'),
('tiara', 'Retailer', 'Yes', 'Yes', 'Yes', 'Yes'),
('tiara', 'Retailercategory', 'Yes', 'Yes', 'Yes', 'Yes'),
('tiara', 'Pricelist', 'Yes', 'Yes', 'Yes', 'Yes'),
('tiara', 'PricelistLink', 'Yes', 'Yes', 'Yes', 'Yes'),
('tiara', 'scheme', 'Yes', 'Yes', 'Yes', 'Yes'),
('tiara', 'Failure Mode', 'Yes', 'Yes', 'Yes', 'Yes'),
('tiara', 'Branch Master', 'Yes', 'Yes', 'Yes', 'Yes'),
('tiara', 'State Master', 'Yes', 'Yes', 'Yes', 'Yes'),
('tiara', 'Region Master', 'Yes', 'Yes', 'Yes', 'Yes'),
('tiara', 'Country Master', 'Yes', 'Yes', 'Yes', 'Yes'),
('tiara', 'masterimport', 'Yes', 'No', 'Yes', 'No'),
('tiara', 'configuration', 'Yes', 'No', 'Yes', 'No'),
('tiara', 'User Creation', 'Yes', 'Yes', 'No', 'No'),
('tiara', 'User rights Master', 'Yes', 'No', 'No', 'No'),
('tiara', 'SerialNO', 'Yes', 'Yes', 'Yes', 'Yes'),
('tiara', 'Supplier Master', 'Yes', 'Yes', 'Yes', 'Yes'),
('tiara', 'Employee Master', 'Yes', 'Yes', 'Yes', 'Yes'),
('tally', 'oemmaster', 'No', 'No', 'No', 'No'),
('tally', 'ProductGroupmaster', 'Yes', 'Yes', 'Yes', 'Yes'),
('tally', 'Productssegment', 'Yes', 'Yes', 'Yes', 'Yes'),
('tally', 'Productstype', 'Yes', 'Yes', 'Yes', 'Yes'),
('tally', 'productuom', 'Yes', 'Yes', 'Yes', 'Yes'),
('tally', 'proratalogic', 'No', 'No', 'No', 'No'),
('tally', 'Productsdetails', 'Yes', 'Yes', 'Yes', 'Yes'),
('tally', 'Productswarranty', 'No', 'No', 'No', 'No'),
('tally', 'Productsmapping', 'No', 'No', 'No', 'No'),
('tally', 'Service Compensation Master', 'No', 'No', 'No', 'No'),
('tally', 'Franchise Master', 'Yes', 'Yes', 'Yes', 'Yes'),
('tally', 'Vehiclemake', 'No', 'No', 'No', 'No'),
('tally', 'Vehiclesegment', 'No', 'No', 'No', 'No'),
('tally', 'Vehiclemodel', 'No', 'No', 'No', 'No'),
('tally', 'Retailer', 'Yes', 'Yes', 'Yes', 'Yes'),
('tally', 'Retailercategory', 'Yes', 'Yes', 'Yes', 'Yes'),
('tally', 'Pricelist', 'Yes', 'Yes', 'Yes', 'Yes'),
('tally', 'PricelistLink', 'Yes', 'Yes', 'Yes', 'Yes'),
('tally', 'scheme', 'Yes', 'Yes', 'Yes', 'Yes'),
('tally', 'Failure Mode', 'No', 'No', 'No', 'No'),
('tally', 'Branch Master', 'Yes', 'Yes', 'Yes', 'Yes'),
('tally', 'State Master', 'Yes', 'Yes', 'Yes', 'Yes'),
('tally', 'Region Master', 'Yes', 'Yes', 'Yes', 'Yes'),
('tally', 'Country Master', 'Yes', 'Yes', 'Yes', 'Yes'),
('tally', 'masterimport', 'Yes', 'No', 'Yes', 'No'),
('tally', 'configuration', 'Yes', 'No', 'Yes', 'No'),
('tally', 'User Creation', 'Yes', 'Yes', 'Yes', 'Yes'),
('tally', 'User rights Master', 'Yes', 'No', 'Yes', 'No'),
('tally', 'SerialNO', 'Yes', 'Yes', 'Yes', 'Yes'),
('tally', 'Supplier Master', 'No', 'No', 'No', 'No'),
('tally', 'Employee Master', 'Yes', 'Yes', 'Yes', 'Yes'),
('admin', 'Report rights', 'Yes', 'No', 'Yes', 'No'),
('tiara', 'Report rights', 'Yes', 'No', 'No', 'No'),
('tally', 'Report rights', 'Yes', 'No', 'Yes', 'No'),
('Jayapremna', 'oemmaster', 'No', 'No', 'No', 'No'),
('Jayapremna', 'ProductGroupmaster', 'No', 'No', 'No', 'No'),
('Jayapremna', 'Productssegment', 'No', 'No', 'No', 'No'),
('Jayapremna', 'Productstype', 'No', 'No', 'No', 'No'),
('Jayapremna', 'productuom', 'No', 'No', 'No', 'No'),
('Jayapremna', 'proratalogic', 'No', 'No', 'No', 'No'),
('Jayapremna', 'Productsdetails', 'No', 'No', 'No', 'No'),
('Jayapremna', 'Productswarranty', 'No', 'No', 'No', 'No'),
('Jayapremna', 'Productsmapping', 'No', 'No', 'No', 'No'),
('Jayapremna', 'Service Compensation Master', 'No', 'No', 'No', 'No'),
('Jayapremna', 'Franchise Master', 'No', 'No', 'No', 'No'),
('Jayapremna', 'Vehiclemake', 'No', 'No', 'No', 'No'),
('Jayapremna', 'Vehiclesegment', 'No', 'No', 'No', 'No'),
('Jayapremna', 'Vehiclemodel', 'No', 'No', 'No', 'No'),
('Jayapremna', 'Retailer', 'No', 'No', 'No', 'No'),
('Jayapremna', 'Retailercategory', 'No', 'No', 'No', 'No'),
('Jayapremna', 'Pricelist', 'No', 'No', 'No', 'No'),
('Jayapremna', 'PricelistLink', 'No', 'No', 'No', 'No'),
('Jayapremna', 'scheme', 'No', 'No', 'No', 'No'),
('Jayapremna', 'Failure Mode', 'No', 'No', 'No', 'No'),
('Jayapremna', 'Branch Master', 'No', 'No', 'No', 'No'),
('Jayapremna', 'State Master', 'No', 'No', 'No', 'No'),
('Jayapremna', 'Region Master', 'No', 'No', 'No', 'No'),
('Jayapremna', 'Country Master', 'No', 'No', 'No', 'No'),
('Jayapremna', 'masterimport', 'No', 'No', 'No', 'No'),
('Jayapremna', 'configuration', 'No', 'No', 'No', 'No'),
('Jayapremna', 'User Creation', 'No', 'No', 'No', 'No'),
('Jayapremna', 'User rights Master', 'No', 'No', 'No', 'No'),
('Jayapremna', 'Report rights ', 'No', 'No', 'No', 'No'),
('Jayapremna', 'SerialNO', 'No', 'No', 'No', 'No'),
('Jayapremna', 'Supplier Master', 'No', 'No', 'No', 'No'),
('Jayapremna', 'Employee Master', 'No', 'No', 'No', 'No');

-- --------------------------------------------------------

--
-- Table structure for table `vehiclemakemaster`
--

CREATE TABLE IF NOT EXISTS `vehiclemakemaster` (
  `MakeNo` varchar(15) NOT NULL DEFAULT '',
  `MakeName` varchar(50) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `m_date` datetime DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`MakeNo`),
  UNIQUE KEY `MakeName` (`MakeName`),
  KEY `Id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `vehiclemodel`
--

CREATE TABLE IF NOT EXISTS `vehiclemodel` (
  `modelcode` varchar(15) NOT NULL DEFAULT '',
  `modelname` varchar(50) DEFAULT NULL,
  `MakeName` varchar(50) DEFAULT NULL,
  `segmentname` varchar(50) DEFAULT NULL,
  `ProductGroup` varchar(50) NOT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `m_date` datetime DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`modelcode`),
  UNIQUE KEY `modelname` (`modelname`),
  KEY `Id` (`id`),
  KEY `Id_2` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `vehiclesegmentmaster`
--

CREATE TABLE IF NOT EXISTS `vehiclesegmentmaster` (
  `segmentcode` varchar(15) NOT NULL DEFAULT '',
  `segmentname` varchar(50) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `m_date` datetime DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`segmentcode`),
  UNIQUE KEY `segmentname` (`segmentname`),
  KEY `Id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_branch1`
--
CREATE TABLE IF NOT EXISTS `view_branch1` (
`region` varchar(50)
,`id` int(11)
,`country` varchar(50)
,`branchcode` varchar(15)
,`branchname` varchar(50)
,`countryname` varchar(50)
,`RegionName` varchar(50)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_closingstock`
--
CREATE TABLE IF NOT EXISTS `view_closingstock` (
`franchiseecode` varchar(50)
,`productcode` varchar(50)
,`opdate` date
,`StockonHand` decimal(33,0)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_fbr`
--
CREATE TABLE IF NOT EXISTS `view_fbr` (
`Franchisecode` varchar(50)
,`id` int(11)
,`Franchisename` varchar(50)
,`Branch` varchar(50)
,`branchname` varchar(50)
,`Region` varchar(50)
,`RegionName` varchar(50)
,`city` varchar(50)
,`statecode` varchar(50)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_francheese_retailer`
--
CREATE TABLE IF NOT EXISTS `view_francheese_retailer` (
`Franchisecode` varchar(50)
,`id` int(11)
,`RetailerCode` varchar(15)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_ftemp`
--
CREATE TABLE IF NOT EXISTS `view_ftemp` (
`franchiseecode` varchar(50)
,`f_date` date
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_pgroup`
--
CREATE TABLE IF NOT EXISTS `view_pgroup` (
`ProductSegmentCode` varchar(50)
,`psegmentname` varchar(50)
,`pgroupcode` varchar(50)
,`id` int(11)
,`ProductGroup` varchar(50)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_pgvehiclemodel`
--
CREATE TABLE IF NOT EXISTS `view_pgvehiclemodel` (
`modelcode` varchar(15)
,`modelname` varchar(50)
,`ProductCode` varchar(50)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_pmap`
--
CREATE TABLE IF NOT EXISTS `view_pmap` (
`ProductDescription` varchar(100)
,`pmpc` varchar(50)
,`ProductCode` varchar(50)
,`MapProductCode` varchar(50)
,`id` int(11)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_price`
--
CREATE TABLE IF NOT EXISTS `view_price` (
`PriceListCode` varchar(100)
,`pricelistname` varchar(100)
,`Country` varchar(100)
,`State` varchar(100)
,`Branch` varchar(100)
,`Franchisee` varchar(100)
,`effectivedate` date
,`applicabledate` date
,`productcode` varchar(50)
,`productdescription` varchar(100)
,`mrp` int(20)
,`fprice` int(20)
,`rprice` int(20)
,`iprice` int(20)
,`Status` varchar(30)
,`InsertDate` date
,`Deliverydae` date
,`Id` int(11)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_productdtetails`
--
CREATE TABLE IF NOT EXISTS `view_productdtetails` (
`pcode` varchar(50)
,`pdescription` varchar(100)
,`ptypecode` varchar(50)
,`ptypename` varchar(50)
,`psegmentcode` varchar(50)
,`psegmentname` varchar(50)
,`pgroupcode` varchar(50)
,`pgroupname` varchar(50)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_producttype`
--
CREATE TABLE IF NOT EXISTS `view_producttype` (
`ProductCode` varchar(50)
,`ProductDescription` varchar(100)
,`ptypecode` varchar(50)
,`ProductTypeName` varchar(50)
,`segmentcode` varchar(50)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_psegment`
--
CREATE TABLE IF NOT EXISTS `view_psegment` (
`ProductCode` varchar(50)
,`ProductDescription` varchar(100)
,`ptypecode` varchar(50)
,`ProductTypeName` varchar(50)
,`segmentcode` varchar(50)
,`ProductSegment` varchar(50)
,`pgroup` varchar(50)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_purchasereturn`
--
CREATE TABLE IF NOT EXISTS `view_purchasereturn` (
`RetDate` date
,`ProductCode` varchar(50)
,`ProductDescription` varchar(50)
,`purchaseretqty` decimal(51,0)
,`FranchiseCode` varchar(50)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_rbrs`
--
CREATE TABLE IF NOT EXISTS `view_rbrs` (
`Franchisecode` varchar(50)
,`id` int(11)
,`Franchisename` varchar(50)
,`Branch` varchar(50)
,`branchname` varchar(50)
,`Region` varchar(50)
,`RegionName` varchar(50)
,`city` varchar(50)
,`statecode` varchar(50)
,`statename` varchar(50)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_region1`
--
CREATE TABLE IF NOT EXISTS `view_region1` (
`RegionCode` varchar(15)
,`RegionName` varchar(50)
,`Country` varchar(50)
,`countryname` varchar(50)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_retailersales`
--
CREATE TABLE IF NOT EXISTS `view_retailersales` (
`salesno` varchar(50)
,`salesdates` date
,`productcode` varchar(50)
,`productdes` varchar(100)
,`schemename` varchar(50)
,`VoucherType` varchar(50)
,`retailername` varchar(100)
,`voucherstatus` varchar(30)
,`quantity` double
,`franchisename` varchar(50)
,`branchcode` varchar(50)
,`regioncode` varchar(50)
,`regionname` varchar(50)
,`branchname` varchar(50)
,`rate` decimal(53,4)
,`amount` decimal(65,0)
,`franchisecode` varchar(50)
,`TaxAmount` double
,`grossamt` double
,`pricelevel` varchar(30)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_retailersalesledger`
--
CREATE TABLE IF NOT EXISTS `view_retailersalesledger` (
`SalesNo` varchar(100)
,`salesdates` date
,`taxamount` double
,`franchisecode` varchar(100)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_rptfranch`
--
CREATE TABLE IF NOT EXISTS `view_rptfranch` (
`RegionCode` varchar(15)
,`RegionName` varchar(50)
,`CountryName` varchar(50)
,`branchcode` varchar(15)
,`branchname` varchar(50)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_rptfrnfin`
--
CREATE TABLE IF NOT EXISTS `view_rptfrnfin` (
`RegionCode` varchar(15)
,`RegionName` varchar(50)
,`CountryName` varchar(50)
,`branchcode` varchar(15)
,`branchname` varchar(50)
,`Franchisecode` varchar(50)
,`Franchisename` varchar(50)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_rptproduct`
--
CREATE TABLE IF NOT EXISTS `view_rptproduct` (
`ProductSegmentCode` varchar(50)
,`psegmentname` varchar(50)
,`pgroupcode` varchar(50)
,`id` int(11)
,`ProductGroup` varchar(50)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_rptproductfin`
--
CREATE TABLE IF NOT EXISTS `view_rptproductfin` (
`pgroupcode` varchar(50)
,`ProductGroup` varchar(50)
,`ProductSegmentCode` varchar(50)
,`psegmentname` varchar(50)
,`ProductTypeCode` varchar(15)
,`ProductTypeName` varchar(50)
,`ProductCode` varchar(50)
,`ProductDescription` varchar(100)
,`id` int(11)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_rpt_product2`
--
CREATE TABLE IF NOT EXISTS `view_rpt_product2` (
`pgroupcode` varchar(50)
,`ProductGroup` varchar(50)
,`ProductSegmentCode` varchar(50)
,`psegmentname` varchar(50)
,`ProductTypeCode` varchar(15)
,`ProductTypeName` varchar(50)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_selectregion`
--
CREATE TABLE IF NOT EXISTS `view_selectregion` (
`branchname` varchar(50)
,`regioncode` varchar(15)
,`regionname` varchar(50)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_status`
--
CREATE TABLE IF NOT EXISTS `view_status` (
`Franchisecode` varchar(50)
,`Franchisename` varchar(50)
,`Branch` varchar(50)
,`branchname` varchar(50)
,`RegionName` varchar(50)
,`downloaddate` date
,`Uploaddate` date
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_vehicledetails`
--
CREATE TABLE IF NOT EXISTS `view_vehicledetails` (
`id` int(11)
,`modelname` varchar(50)
,`makecode` varchar(50)
,`segmentcode` varchar(50)
,`makename` varchar(50)
,`segmentname` varchar(50)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_vmodel`
--
CREATE TABLE IF NOT EXISTS `view_vmodel` (
`modelcode` varchar(15)
,`modelname` varchar(50)
,`MakeName` varchar(50)
,`segmentname` varchar(50)
,`ProductGroup` varchar(50)
);
-- --------------------------------------------------------

--
-- Table structure for table `weblog`
--

CREATE TABLE IF NOT EXISTS `weblog` (
  `userid` varchar(50) NOT NULL,
  `accesstype` varchar(100) NOT NULL,
  `accessform` varchar(100) DEFAULT NULL,
  `accesstime` datetime NOT NULL,
  `auto_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`auto_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=452 ;

--
-- Dumping data for table `weblog`
--

INSERT INTO `weblog` (`userid`, `accesstype`, `accessform`, `accesstime`, `auto_id`) VALUES
('admin', 'Logout', '', '2015-02-19 10:51:13', 143),
('admin', 'Login', '', '2015-02-19 12:33:39', 144),
('admin', 'Report Access', 'Purchase Report -> Purchase Report', '2015-02-19 12:34:56', 145),
('admin', 'Logout', '', '2015-02-19 14:01:56', 146),
('admin', 'Login', '', '2015-02-20 10:12:38', 147),
('admin', 'Login', '', '2015-02-20 11:08:36', 148),
('admin', 'Login', '', '2015-02-24 14:21:10', 149),
('admin', 'Login', '', '2015-03-04 11:07:55', 150),
('admin', 'Login', '', '2015-03-04 11:13:49', 151),
('admin', 'Logout', '', '2015-03-04 12:45:19', 152),
('admin', 'Login', '', '2015-03-04 12:45:24', 153),
('admin', 'Logout', '', '2015-03-04 13:25:06', 154),
('admin', 'Logout', '', '2015-03-04 14:02:11', 155),
('admin', 'Login', '', '2015-03-04 14:28:20', 156),
('admin', 'Report Access', 'Purchase Report -> Purchase Report', '2015-03-04 14:57:33', 157),
('admin', 'Report Access', 'Sales Report -> Sales Register', '2015-03-04 14:59:12', 158),
('admin', 'Report Access', 'Stock Report -> Stock Ledger', '2015-03-04 15:00:53', 159),
('admin', 'Report Access', 'Stock Report -> Stock Ledger', '2015-03-04 15:02:17', 160),
('admin', 'Report Access', 'Stock Report -> Stock Ledger', '2015-03-04 15:02:33', 161),
('admin', 'Report Access', 'Stock Report -> Stock Ledger', '2015-03-04 15:04:21', 162),
('admin', 'Report Access', 'Purchase Report -> Purchase Report', '2015-03-04 15:05:43', 163),
('admin', 'Report Access', 'Stock Report -> Stock Ledger', '2015-03-04 15:06:14', 164),
('admin', 'Report Access', 'Stock Report -> Stock Ledger', '2015-03-04 15:06:55', 165),
('admin', 'Report Access', 'Stock Report -> Stock Ledger', '2015-03-04 15:07:09', 166),
('admin', 'Report Access', 'Stock Report -> Stock Ledger', '2015-03-04 15:09:39', 167),
('admin', 'Report Access', 'Stock Report -> Stock Ledger', '2015-03-04 15:09:56', 168),
('admin', 'Report Access', 'Stock Report -> Stock Ledger', '2015-03-04 15:10:09', 169),
('admin', 'Report Access', 'Stock Report -> Stock Ledger', '2015-03-04 15:11:15', 170),
('admin', 'Report Access', 'Stock Report -> Stock Ledger', '2015-03-04 15:11:30', 171),
('admin', 'Report Access', 'Stock Report -> Stock Ledger', '2015-03-04 15:12:12', 172),
('admin', 'Report Access', 'Stock Report -> Stock Ledger', '2015-03-04 15:12:32', 173),
('admin', 'Report Access', 'Sales Report -> Sales Register', '2015-03-04 15:13:37', 174),
('admin', 'Login', '', '2015-03-04 15:34:46', 175),
('admin', 'Report Access', 'Stock Report -> Stock Ledger', '2015-03-04 15:36:42', 176),
('admin', 'Report Access', 'Stock Report -> Stock Ledger', '2015-03-04 15:36:56', 177),
('admin', 'Report Access', 'Purchase Report -> Purchase Report', '2015-03-04 15:42:40', 178),
('admin', 'Report Access', 'Stock Report -> Stock Ledger', '2015-03-04 15:43:01', 179),
('admin', 'Report Access', 'Stock Report -> Stock Ledger', '2015-03-04 15:58:09', 180),
('admin', 'Report Access', 'Stock Report -> Stock Ledger', '2015-03-04 15:58:32', 181),
('admin', 'Report Access', 'Sales Report -> Sales Report', '2015-03-04 15:59:17', 182),
('admin', 'Report Access', 'Sales Report -> Sales Report', '2015-03-04 15:59:42', 183),
('admin', 'Report Access', 'Sales Report -> Sales Report', '2015-03-04 16:02:17', 184),
('admin', 'Report Access', 'Purchase Report -> Purchase Report', '2015-03-04 16:11:36', 185),
('admin', 'Report Access', 'Sales Report -> Sales Report', '2015-03-04 16:12:21', 186),
('admin', 'Report Access', 'Sales Report -> Sales Report', '2015-03-04 16:15:48', 187),
('admin', 'Report Access', 'Purchase Report -> Purchase Report', '2015-03-04 16:34:10', 188),
('admin', 'Report Access', 'Purchase Report -> Purchase Report', '2015-03-04 16:56:19', 189),
('admin', 'Logout', '', '2015-03-04 19:29:00', 190),
('', 'Logout', '', '2015-03-05 11:44:49', 191),
('admin', 'Login', '', '2015-03-05 11:44:58', 192),
('admin', 'Report Access', 'Purchase Report -> Purchase Report', '2015-03-05 12:55:24', 193),
('admin', 'Report Access', 'Sales Report -> Sales Report', '2015-03-05 13:31:12', 194),
('admin', 'Report Access', 'Purchase Report -> Purchase Report', '2015-03-05 13:34:55', 195),
('admin', 'Report Access', 'Purchase Report -> Purchase Report', '2015-03-05 13:39:44', 196),
('admin', 'Report Access', 'Purchase Report -> Purchase Report', '2015-03-05 13:39:52', 197),
('admin', 'Report Access', 'Transaction Report -> Product Wise Category Wise Transaction Report', '2015-03-05 13:49:04', 198),
('admin', 'Login', '', '2015-03-06 15:41:55', 199),
('admin', 'Login', '', '2015-03-07 13:41:11', 200),
('admin', 'Report Access', 'Sales Report -> Sales Register', '2015-03-07 13:42:15', 201),
('admin', 'Report Access', 'Sales Report -> Sales Register', '2015-03-07 13:42:29', 202),
('admin', 'Login', '', '2015-03-09 10:08:24', 203),
('admin', 'Report Access', 'Purchase Report -> Purchase Report', '2015-03-09 10:33:25', 204),
('admin', 'Report Access', 'Purchase Report -> Purchase Report', '2015-03-09 10:33:32', 205),
('admin', 'Login', '', '2015-03-09 11:03:51', 206),
('admin', 'Report Access', 'Sales Report -> Sales Report', '2015-03-09 11:04:27', 207),
('admin', 'Logout', '', '2015-03-09 11:31:45', 208),
('admin', 'Logout', '', '2015-03-09 12:51:11', 209),
('admin', 'Login', '', '2015-03-09 12:51:19', 210),
('', 'Logout', '', '2015-03-09 13:31:05', 211),
('admin', 'Login', '', '2015-03-09 13:31:12', 212),
('admin', 'Report Access', 'Sales Report -> Sales Report', '2015-03-09 13:34:47', 213),
('admin', 'Report Access', 'Sales Report -> Sales Report', '2015-03-09 13:35:41', 214),
('admin', 'Report Access', 'Stock Report -> Data Exchange', '2015-03-09 13:39:24', 215),
('admin', 'Logout', '', '2015-03-09 14:16:49', 216),
('admin', 'Logout', '', '2015-03-09 14:16:50', 217),
('admin', 'Logout', '', '2015-03-09 14:23:30', 218),
('admin', 'Login', '', '2015-03-09 17:08:08', 219),
('admin', 'Report Access', 'Serial Number History', '2015-03-09 17:08:17', 220),
('admin', 'Logout', '', '2015-03-09 17:08:30', 221),
('', 'Logout', '', '2015-03-10 10:03:11', 222),
('admin', 'Login', '', '2015-03-10 10:03:18', 223),
('admin', 'Logout', '', '2015-03-10 10:04:29', 224),
('admin', 'Login', '', '2015-03-10 10:04:39', 225),
('admin', 'Logout', '', '2015-03-10 10:06:45', 226),
('', 'Logout', '', '2015-03-10 10:06:55', 227),
('admin', 'Login', '', '2015-03-10 10:07:08', 228),
('admin', 'Logout', '', '2015-03-10 10:07:47', 229),
('admin', 'Login', '', '2015-03-11 16:19:57', 230),
('admin', 'Report Access', 'Serial Number History', '2015-03-11 16:20:46', 231),
('admin', 'Logout', '', '2015-03-11 16:21:16', 232),
('admin', 'Login', '', '2015-03-12 10:17:05', 233),
('admin', 'Logout', '', '2015-03-12 11:07:07', 234),
('admin', 'Login', '', '2015-03-12 11:17:12', 235),
('admin', 'Logout', '', '2015-03-12 12:07:13', 236),
('admin', 'Login', '', '2015-03-13 17:13:32', 237),
('admin', 'Logout', '', '2015-03-13 17:21:52', 238),
('admin', 'Login', '', '2015-03-13 18:08:46', 239),
('admin', 'Login', '', '2015-03-16 08:11:01', 240),
('admin', 'Report Access', 'Sales Report -> Item Wise Sales Summary Report', '2015-03-16 08:11:16', 241),
('admin', 'Report Access', 'Sales Report -> Item Wise Sales Summary Report', '2015-03-16 08:12:09', 242),
('admin', 'Logout', '', '2015-03-16 08:12:11', 243),
('', 'Logout', '', '2015-03-16 10:14:23', 244),
('', 'Logout', '', '2015-03-16 10:14:28', 245),
('admin', 'Login', '', '2015-03-16 10:14:47', 246),
('admin', 'Login', '', '2015-03-16 10:29:04', 247),
('admin', 'Report Access', 'Sales Report -> Sales Summary Report', '2015-03-16 10:29:30', 248),
('admin', 'Report Access', 'Sales Report -> Sales Summary Report', '2015-03-16 10:29:34', 249),
('admin', 'Report Access', 'Sales Report -> Sales Summary Report', '2015-03-16 10:29:38', 250),
('admin', 'Report Access', 'Sales Report -> Sales Summary Report', '2015-03-16 10:29:47', 251),
('admin', 'Login', '', '2015-03-16 10:51:23', 252),
('admin', 'Logout', '', '2015-03-16 11:13:58', 253),
('admin', 'Logout', '', '2015-03-16 11:24:31', 254),
('admin', 'Report Access', 'Stock Report -> Stock Ledger', '2015-03-16 12:02:39', 255),
('admin', 'Login', '', '2015-03-16 12:46:11', 256),
('admin', 'Login', '', '2015-03-16 12:58:25', 257),
('admin', 'Login', '', '2015-03-16 15:52:56', 258),
('admin', 'Report Access', 'Stock Report -> Stock Ledger', '2015-03-16 15:53:14', 259),
('admin', 'Login', '', '2015-03-16 15:55:31', 260),
('admin', 'Report Access', 'Stock Report -> Stock Ledger', '2015-03-16 15:56:52', 261),
('admin', 'Report Access', 'Stock Report -> Stock Ledger', '2015-03-16 15:58:11', 262),
('admin', 'Report Access', 'Stock Report -> Stock Ledger', '2015-03-16 16:03:36', 263),
('admin', 'Report Access', 'Purchase Report -> Purchase Report', '2015-03-16 16:04:00', 264),
('admin', 'Report Access', 'Purchase Report -> Purchase Report', '2015-03-16 16:04:41', 265),
('admin', 'Report Access', 'Stock Report -> Stock Ledger', '2015-03-16 16:05:17', 266),
('admin', 'Login', '', '2015-03-16 16:06:39', 267),
('admin', 'Report Access', 'Stock Report -> Stock Ledger', '2015-03-16 16:12:48', 268),
('admin', 'Report Access', 'Stock Report -> Stock Ledger', '2015-03-16 16:46:46', 269),
('admin', 'Logout', '', '2015-03-16 17:43:31', 270),
('admin', 'Login', '', '2015-03-16 18:40:58', 271),
('admin', 'Logout', '', '2015-03-16 18:41:04', 272),
('', 'Logout', '', '2015-03-16 18:41:19', 273),
('admin', 'Login', '', '2015-03-16 18:42:16', 274),
('admin', 'Logout', '', '2015-03-16 18:43:08', 275),
('', 'Logout', '', '2015-03-16 18:43:38', 276),
('', 'Logout', '', '2015-03-16 18:43:44', 277),
('', 'Logout', '', '2015-03-16 18:43:53', 278),
('', 'Logout', '', '2015-03-16 18:44:04', 279),
('', 'Logout', '', '2015-03-16 18:44:09', 280),
('', 'Logout', '', '2015-03-16 18:44:29', 281),
('', 'Logout', '', '2015-03-16 18:45:46', 282),
('', 'Logout', '', '2015-03-16 18:46:02', 283),
('', 'Logout', '', '2015-03-16 18:52:31', 284),
('', 'Logout', '', '2015-03-16 18:52:40', 285),
('', 'Logout', '', '2015-03-16 18:54:34', 286),
('', 'Logout', '', '2015-03-16 18:56:09', 287),
('', 'Logout', '', '2015-03-16 18:57:11', 288),
('', 'Logout', '', '2015-03-16 18:57:21', 289),
('tally', 'Login', '', '2015-03-16 18:59:07', 290),
('tally', 'Logout', '', '2015-03-16 18:59:20', 291),
('tiara', 'Login', '', '2015-03-16 18:59:28', 292),
('tiara', 'Logout', '', '2015-03-16 18:59:43', 293),
('admin', 'Login', '', '2015-03-16 19:01:26', 294),
('admin', 'Logout', '', '2015-03-16 19:01:35', 295),
('admin', 'Login', '', '2015-03-16 19:03:11', 296),
('admin', 'Login', '', '2015-03-17 13:00:12', 297),
('admin', 'Logout', '', '2015-03-17 16:58:35', 298),
('admin', 'Login', '', '2015-03-17 17:10:47', 299),
('admin', 'Login', '', '2015-03-19 16:57:39', 300),
('admin', 'Login', '', '2015-03-19 18:49:52', 301),
('admin', 'Login', '', '2015-03-20 10:24:28', 302),
('admin', 'Logout', '', '2015-03-20 11:54:32', 303),
('admin', 'Login', '', '2015-03-23 18:27:05', 304),
('admin', 'Login', '', '2015-03-24 10:55:29', 305),
('admin', 'Login', '', '2015-03-24 18:49:28', 306),
('admin', 'Login', '', '2015-03-26 10:02:28', 307),
('admin', 'Logout', '', '2015-03-26 10:07:14', 308),
('tally', 'Login', '', '2015-03-26 10:07:20', 309),
('tally', 'Logout', '', '2015-03-26 10:07:33', 310),
('admin', 'Login', '', '2015-03-26 10:07:39', 311),
('admin', 'Report Access', 'Purchase Report -> Purchase Report', '2015-03-26 10:07:57', 312),
('admin', 'Report Access', 'Purchase Report -> Purchase Report', '2015-03-26 10:07:58', 313),
('admin', 'Report Access', 'Purchase Report -> Purchase Report', '2015-03-26 10:08:09', 314),
('admin', 'Login', '', '2015-03-26 13:48:29', 315),
('admin', 'Report Access', 'Sales Report -> Sales Register', '2015-03-26 13:54:26', 316),
('admin', 'Report Access', 'Sales Report -> Sales Register', '2015-03-26 13:56:31', 317),
('admin', 'Report Access', 'Purchase Report -> Purchase Order', '2015-03-26 13:57:00', 318),
('admin', 'Report Access', 'Purchase Report -> Purchase Order', '2015-03-26 14:01:28', 319),
('admin', 'Report Access', 'Purchase Report -> Purchase Returns', '2015-03-26 14:01:58', 320),
('admin', 'Report Access', 'Purchase Report -> Purchase Report', '2015-03-26 14:04:15', 321),
('admin', 'Report Access', 'Purchase Report -> Purchase Report', '2015-03-26 14:12:07', 322),
('admin', 'Report Access', 'Purchase Report -> Purchase Report', '2015-03-26 14:15:02', 323),
('admin', 'Report Access', 'Sales Report -> Sales Report', '2015-03-26 14:15:36', 324),
('admin', 'Report Access', 'Sales Report -> Sales Report', '2015-03-26 14:16:21', 325),
('admin', 'Report Access', 'Sales Report -> Sales Report', '2015-03-26 14:16:33', 326),
('admin', 'Report Access', 'Stock Report -> Sales Returns', '2015-03-26 14:16:48', 327),
('admin', 'Report Access', 'Sales Report -> Sales Report', '2015-03-26 14:27:32', 328),
('admin', 'Report Access', 'Stock Report -> Stock Ledger', '2015-03-26 14:30:46', 329),
('admin', 'Login', '', '2015-03-26 14:34:04', 330),
('admin', 'Report Access', 'Purchase Report -> Purchase Order', '2015-03-26 15:45:26', 331),
('admin', 'Report Access', 'Purchase Report -> Purchase Order', '2015-03-26 15:55:41', 332),
('admin', 'Report Access', 'Purchase Report -> Purchase Order', '2015-03-26 15:55:52', 333),
('admin', 'Report Access', 'Purchase Report -> Purchase Order', '2015-03-26 15:56:12', 334),
('admin', 'Logout', '', '2015-03-26 17:23:53', 335),
('admin', 'Login', '', '2015-03-26 17:25:04', 336),
('admin', 'Report Access', 'Stock Report -> Data Exchange', '2015-03-26 17:32:25', 337),
('admin', 'Report Access', 'Admin Report -> Item Wise Sales Summary Report', '2015-03-26 17:32:55', 338),
('admin', 'Report Access', 'Admin Report -> Day Wise Synch Status - Master Upload', '2015-03-26 17:33:19', 339),
('admin', 'Report Access', 'Admin Report -> Day Wise Synch Status - Master Upload', '2015-03-26 17:33:30', 340),
('admin', 'Report Access', 'Admin Report -> Month Wise Synch Status - Transaction Download', '2015-03-26 17:33:46', 341),
('admin', 'Report Access', 'Sales Report -> Item Wise Sales Summary Report', '2015-03-26 17:34:07', 342),
('admin', 'Report Access', 'Stock Report -> Data Exchange', '2015-03-26 17:34:45', 343),
('admin', 'Logout', '', '2015-03-26 17:35:02', 344),
('tally', 'Login', '', '2015-03-26 17:35:09', 345),
('tally', 'Logout', '', '2015-03-26 18:18:27', 346),
('', 'Logout', '', '2015-03-27 09:18:02', 347),
('tally', 'Login', '', '2015-03-27 09:18:16', 348),
('tally', 'Report Access', 'Sales Report -> Sales Register', '2015-03-27 09:39:24', 349),
('tally', 'Report Access', 'Stock Report -> Sales Returns', '2015-03-27 09:40:00', 350),
('tally', 'Logout', '', '2015-03-27 11:00:05', 351),
('tally', 'Login', '', '2015-03-27 11:00:18', 352),
('tally', 'Report Access', 'Purchase Report -> Purchase Report', '2015-03-27 11:02:44', 353),
('tally', 'Logout', '', '2015-03-27 11:57:44', 354),
('tally', 'Login', '', '2015-03-27 12:20:35', 355),
('tally', 'Report Access', 'Sales Report -> Sales Summary Report', '2015-03-27 12:35:47', 356),
('tally', 'Report Access', 'Purchase Report -> Purchase Report', '2015-03-27 12:48:12', 357),
('tally', 'Report Access', 'Stock Report -> Data Exchange', '2015-03-27 12:49:29', 358),
('tally', 'Report Access', 'Stock Report -> Stock Ledger', '2015-03-27 12:57:37', 359),
('tally', 'Logout', '', '2015-03-27 18:57:31', 360),
('admin', 'Login', '', '2015-03-30 10:26:53', 361),
('', 'Logout', '', '2015-03-30 10:26:59', 362),
('admin', 'Login', '', '2015-03-30 10:27:06', 363),
('admin', 'Report Access', 'Sales Report -> Sales Report', '2015-03-30 10:32:19', 364),
('admin', 'Logout', '', '2015-03-30 11:24:58', 365),
('admin', 'Logout', '', '2015-03-30 11:40:49', 366),
('admin', 'Login', '', '2015-03-30 16:51:55', 367),
('admin', 'Logout', '', '2015-03-30 16:52:58', 368),
('admin', 'Login', '', '2015-03-30 18:47:47', 369),
('admin', 'Logout', '', '2015-03-30 18:53:07', 370),
('', 'Logout', '', '2015-03-30 18:53:12', 371),
('admin', 'Login', '', '2015-03-30 18:53:17', 372),
('admin', 'Logout', '', '2015-03-30 18:58:12', 373),
('Jayapremnath', 'Login', '', '2015-03-30 18:58:17', 374),
('admin', 'Login', '', '2015-03-31 10:33:34', 375),
('', 'Logout', '', '2015-03-31 13:00:23', 376),
('admin', 'Login', '', '2015-03-31 13:00:27', 377),
('admin', 'Logout', '', '2015-03-31 13:01:54', 378),
('admin', 'Login', '', '2015-03-31 15:14:15', 379),
('admin', 'Logout', '', '2015-03-31 16:40:40', 380),
('admin', 'Login', '', '2015-04-02 10:50:22', 381),
('admin', 'Logout', '', '2015-04-02 10:50:39', 382),
('admin', 'Login', '', '2015-04-02 15:08:08', 383),
('admin', 'Logout', '', '2015-04-02 15:09:14', 384),
('admin', 'Login', '', '2015-04-06 11:32:04', 385),
('admin', 'Report Access', 'Sales Report -> Item Wise Sales Summary Report', '2015-04-06 11:54:57', 386),
('admin', 'Report Access', 'Sales Report -> Item Wise Sales Summary Report', '2015-04-06 11:55:05', 387),
('admin', 'Report Access', 'Sales Report -> Item Wise Sales Summary Report', '2015-04-06 11:55:20', 388),
('admin', 'Report Access', 'Sales Report -> Item Wise Sales Summary Report', '2015-04-06 11:55:36', 389),
('admin', 'Login', '', '2015-04-07 19:57:31', 390),
('admin', 'Logout', '', '2015-04-07 20:07:28', 391),
('admin', 'Login', '', '2015-04-08 10:29:48', 392),
('admin', 'Login', '', '2015-04-08 11:17:33', 393),
('admin', 'Logout', '', '2015-04-08 11:29:27', 394),
('admin', 'Login', '', '2015-04-08 11:29:33', 395),
('admin', 'Logout', '', '2015-04-08 11:33:59', 396),
('admin', 'Login', '', '2015-04-08 11:34:05', 397),
('admin', 'Logout', '', '2015-04-08 13:07:16', 398),
('admin', 'Login', '', '2015-04-08 13:41:04', 399),
('admin', 'Login', '', '2015-04-09 10:31:24', 400),
('admin', 'Login', '', '2015-04-09 11:10:39', 401),
('admin', 'Logout', '', '2015-04-09 11:11:27', 402),
('admin', 'Report Access', 'Sales Report -> Item Wise Sales Summary Report', '2015-04-09 11:17:26', 403),
('admin', 'Report Access', 'Sales Report -> Item Wise Sales Summary Report', '2015-04-09 11:17:35', 404),
('admin', 'Report Access', 'Sales Report -> Item Wise Sales Summary Report', '2015-04-09 11:17:48', 405),
('admin', 'Logout', '', '2015-04-09 11:19:15', 406),
('admin', 'Login', '', '2015-04-09 12:46:51', 407),
('admin', 'Logout', '', '2015-04-09 13:37:27', 408),
('admin', 'Login', '', '2015-04-09 13:39:12', 409),
('admin', 'Logout', '', '2015-04-09 14:29:15', 410),
('admin', 'Login', '', '2015-04-09 14:36:35', 411),
('admin', 'Logout', '', '2015-04-09 14:43:15', 412),
('admin', 'Login', '', '2015-04-09 14:43:24', 413),
('admin', 'Logout', '', '2015-04-09 14:55:42', 414),
('admin', 'Login', '', '2015-04-09 14:55:52', 415),
('admin', 'Logout', '', '2015-04-09 15:14:19', 416),
('admin', 'Login', '', '2015-04-09 15:14:46', 417),
('admin', 'Logout', '', '2015-04-09 15:15:42', 418),
('admin', 'Login', '', '2015-04-09 15:16:26', 419),
('admin', 'Logout', '', '2015-04-09 15:18:36', 420),
('admin', 'Login', '', '2015-04-10 17:32:37', 421),
('admin', 'Login', '', '2015-04-10 21:06:08', 422),
('admin', 'Login', '', '2015-04-14 09:41:26', 423),
('admin', 'Logout', '', '2015-04-14 10:31:34', 424),
('admin', 'Logout', '', '2015-04-14 10:31:34', 425),
('admin', 'Login', '', '2015-04-14 12:18:34', 426),
('admin', 'Logout', '', '2015-04-14 12:23:42', 427),
('admin', 'Login', '', '2015-04-14 12:23:52', 428),
('admin', 'Logout', '', '2015-04-14 12:23:54', 429),
('admin', 'Login', '', '2015-04-15 10:33:41', 430),
('admin', 'Logout', '', '2015-04-15 11:24:06', 431),
('', 'Logout', '', '2015-04-15 14:47:40', 432),
('admin', 'Login', '', '2015-04-15 14:47:44', 433),
('admin', 'Logout', '', '2015-04-15 14:50:08', 434),
('admin', 'Login', '', '2015-04-17 11:06:57', 435),
('admin', 'Logout', '', '2015-04-17 11:27:15', 436),
('admin', 'Logout', '', '2015-04-17 12:39:51', 437),
('admin', 'Login', '', '2015-04-24 10:27:37', 438),
('admin', 'Logout', '', '2015-04-24 11:20:39', 439),
('admin', 'Login', '', '2015-04-27 10:28:40', 440),
('admin', 'Logout', '', '2015-04-27 10:29:11', 441),
('admin', 'Login', '', '2015-04-27 18:57:30', 442),
('admin', 'Logout', '', '2015-04-27 19:00:40', 443),
('admin', 'Login', '', '2015-04-27 20:04:53', 444),
('admin', 'Logout', '', '2015-04-27 20:04:55', 445),
('admin', 'Login', '', '2015-04-28 11:33:48', 446),
('admin', 'Logout', '', '2015-04-28 11:33:51', 447),
('admin', 'Login', '', '2015-04-28 14:08:28', 448),
('admin', 'Logout', '', '2015-04-28 15:17:25', 449),
('admin', 'Login', '', '2015-04-29 10:23:14', 450),
('admin', 'Logout', '', '2015-04-29 10:37:40', 451);

-- --------------------------------------------------------

--
-- Table structure for table `wmaterialreceipt`
--

CREATE TABLE IF NOT EXISTS `wmaterialreceipt` (
  `wmno` varchar(50) NOT NULL DEFAULT '',
  `wmdatedate` date DEFAULT NULL,
  `dcwno` varchar(50) DEFAULT NULL,
  `SalesType` varchar(50) DEFAULT NULL,
  `partyledger` varchar(30) DEFAULT NULL,
  `Decision` varchar(50) DEFAULT NULL,
  `VoucherType` varchar(30) DEFAULT NULL,
  `franchisecode` varchar(50) DEFAULT NULL,
  `voucherstatus` varchar(30) DEFAULT NULL,
  `dcwno_masterid` varchar(45) NOT NULL,
  `masterid` varchar(45) NOT NULL,
  PRIMARY KEY (`masterid`),
  UNIQUE KEY `masterid` (`masterid`),
  KEY `wmno` (`wmno`),
  KEY `index_4` (`wmdatedate`,`VoucherType`,`franchisecode`,`voucherstatus`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `wmaterialreturnledger`
--

CREATE TABLE IF NOT EXISTS `wmaterialreturnledger` (
  `wmno` varchar(50) DEFAULT NULL,
  `wmdatedate` date DEFAULT NULL,
  `Taxledger` varchar(50) DEFAULT NULL,
  `Taxamount` double DEFAULT NULL,
  `franchisecode` varchar(20) DEFAULT NULL,
  `voucherstatus` varchar(30) DEFAULT NULL,
  `masterid` varchar(45) NOT NULL,
  KEY `index_1` (`masterid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `wmaterial_details`
--

CREATE TABLE IF NOT EXISTS `wmaterial_details` (
  `ProductCode` varchar(50) DEFAULT NULL,
  `ProductDescription` varchar(50) DEFAULT NULL,
  `Quantity` int(30) DEFAULT NULL,
  `Rate` double DEFAULT NULL,
  `Amount` float DEFAULT NULL,
  `wmno` varchar(50) DEFAULT NULL,
  `wmdatedate` date DEFAULT NULL,
  `FranchiseCode` varchar(50) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `voucherstatus` varchar(30) DEFAULT NULL,
  `masterid` varchar(45) NOT NULL,
  KEY `index_1` (`ProductCode`,`wmdatedate`,`FranchiseCode`,`voucherstatus`,`masterid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Triggers `wmaterial_details`
--
DROP TRIGGER IF EXISTS `after_wmpurchase_insert`;
DELIMITER //
CREATE TRIGGER `after_wmpurchase_insert` AFTER INSERT ON `wmaterial_details`
 FOR EACH ROW BEGIN
		DECLARE Region_Name VARCHAR(50);
		DECLARE branch_name VARCHAR(50);
		DECLARE Franchise_name VARCHAR(50);
		DECLARE p_description VARCHAR(100);
		DECLARE p_typename VARCHAR(50);
		DECLARE p_segmentname VARCHAR(50);
		DECLARE p_groupname VARCHAR(50);
		DECLARE Voucher_Type VARCHAR(50);
		DECLARE P_order VARCHAR(50);

		SELECT dcwno,VoucherType
		INTO @P_order,@Voucher_Type
		FROM wmaterialreceipt
		WHERE wmaterialreceipt.masterid= NEW.masterid;

		SELECT branchname,RegionName,Franchisename
		INTO @branch_name,@Region_Name,@Franchise_name
		FROM view_rbrs
		WHERE view_rbrs.Franchisecode = NEW.FranchiseCode;

		SELECT pdescription,ptypename,psegmentname,pgroupname
		INTO @p_description,@p_typename,@p_segmentname,@p_groupname
		FROM view_productdtetails
		WHERE view_productdtetails.pcode = NEW.ProductCode;

		INSERT INTO r_purchasereport
		(regionname, branchname, franchisecode, franchisename, purchasenumber, purchasedate, PO, productcode, productdes, pgroupname, psegmentname, ptypename, vouchertype, quantity, NetAmount, taxamount, grossamt, unique_id)
		VALUES
		(@Region_Name,@branch_name,NEW.FranchiseCode,@Franchise_name,NEW.wmno,NEW.wmdatedate,@P_order,NEW.ProductCode,@p_description,@p_groupname,@p_segmentname,@p_typename,@Voucher_Type,NEW.Quantity,0,0,0,NEW.masterid);
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `after_wmpurchase_update`;
DELIMITER //
CREATE TRIGGER `after_wmpurchase_update` AFTER UPDATE ON `wmaterial_details`
 FOR EACH ROW BEGIN
		DELETE FROM r_purchasereport WHERE unique_id = NEW.masterid;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `after_wmpurchase_delete`;
DELIMITER //
CREATE TRIGGER `after_wmpurchase_delete` AFTER DELETE ON `wmaterial_details`
 FOR EACH ROW BEGIN
		DELETE FROM r_purchasereport WHERE unique_id = OLD.masterid;	
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure for view `oemupload`
--
DROP TABLE IF EXISTS `oemupload`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `oemupload` AS select `a`.`oemcode` AS `oemcode`,`a`.`oemname` AS `oemname`,`b`.`Status` AS `Status`,`b`.`Franchiseecode` AS `Franchiseecode` from (`oemmaster` `a` left join `oemmasterupload` `b` on((`a`.`oemcode` = `b`.`Code`))) group by `a`.`oemcode`,`a`.`oemname`;

-- --------------------------------------------------------

--
-- Structure for view `pricelistlinkinggrid_view`
--
DROP TABLE IF EXISTS `pricelistlinkinggrid_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `pricelistlinkinggrid_view` AS select `pricelistlinkinggrid`.`PriceListCode` AS `PriceListCode`,`pricelistlinkinggrid`.`pricelistname` AS `pricelistname`,`pricelistlinkinggrid`.`Country` AS `Country`,`pricelistlinkinggrid`.`State` AS `State`,`pricelistlinkinggrid`.`Branch` AS `Branch`,`pricelistlinkinggrid`.`Franchisee` AS `Franchisee`,`pricelistlinkinggrid`.`effectivedate` AS `effectivedate`,`pricelistlinkinggrid`.`applicabledate` AS `applicabledate`,`pricelistlinkinggrid`.`productcode` AS `productcode`,`productmaster`.`ProductDescription` AS `productdescription`,`pricelistlinkinggrid`.`mrp` AS `mrp`,`pricelistlinkinggrid`.`fprice` AS `fprice`,`pricelistlinkinggrid`.`rprice` AS `rprice`,`pricelistlinkinggrid`.`iprice` AS `iprice`,`pricelistlinkinggrid`.`Status` AS `Status`,`pricelistlinkinggrid`.`InsertDate` AS `InsertDate`,`pricelistlinkinggrid`.`Deliverydae` AS `Deliverydae`,`pricelistlinkinggrid`.`id` AS `Id` from (`pricelistlinkinggrid` left join `productmaster` on((`pricelistlinkinggrid`.`productcode` = `productmaster`.`ProductCode`))) group by `pricelistlinkinggrid`.`PriceListCode`,`pricelistlinkinggrid`.`pricelistname`,`pricelistlinkinggrid`.`Country`,`pricelistlinkinggrid`.`State`,`pricelistlinkinggrid`.`Branch`,`pricelistlinkinggrid`.`Franchisee`,`pricelistlinkinggrid`.`effectivedate`,`pricelistlinkinggrid`.`applicabledate`,`pricelistlinkinggrid`.`productcode`,`productmaster`.`ProductDescription`,`pricelistlinkinggrid`.`mrp`,`pricelistlinkinggrid`.`fprice`,`pricelistlinkinggrid`.`rprice`,`pricelistlinkinggrid`.`iprice`,`pricelistlinkinggrid`.`Status`,`pricelistlinkinggrid`.`InsertDate`,`pricelistlinkinggrid`.`Deliverydae`,`pricelistlinkinggrid`.`id`;

-- --------------------------------------------------------

--
-- Structure for view `productmapping_view`
--
DROP TABLE IF EXISTS `productmapping_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `productmapping_view` AS select `productmaster`.`ProductDescription` AS `ProductDescription`,`productmapping`.`ProductCode` AS `ProductCode`,`productmapping`.`MapProductCode` AS `MapProductCode`,`productmapping`.`effectivedate` AS `effectivedate`,`productmappingupload`.`Franchiseecode` AS `Franchiseecode`,`productmappingupload`.`Status` AS `Status`,`productmapping`.`Status` AS `pstatus` from ((`productmapping` left join `productmaster` on((`productmapping`.`ProductCode` = `productmaster`.`ProductCode`))) left join `productmappingupload` on((`productmapping`.`ProductCode` = `productmappingupload`.`Code`))) group by `productmaster`.`ProductDescription`,`productmapping`.`ProductCode`,`productmapping`.`MapProductCode`,`productmapping`.`effectivedate`,`productmappingupload`.`Franchiseecode`,`productmappingupload`.`Status`,`productmapping`.`Status`;

-- --------------------------------------------------------

--
-- Structure for view `productmaster_view`
--
DROP TABLE IF EXISTS `productmaster_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `productmaster_view` AS select `productmaster`.`ProductCode` AS `ProductCode`,`productmaster`.`ProductDescription` AS `ProductDescription`,`productmaster`.`ProductType` AS `ProductType`,`productmaster`.`warrantyapplicable` AS `warrantyapplicable`,`productmaster`.`Status` AS `Status`,`productuom`.`productuom` AS `productuom`,`productmaster`.`id` AS `Id` from (`productmaster` left join `productuom` on((`productmaster`.`UOM` = `productuom`.`productuomcode`))) group by `productmaster`.`ProductCode`,`productmaster`.`ProductDescription`,`productmaster`.`ProductType`,`productmaster`.`warrantyapplicable`,`productmaster`.`Status`,`productuom`.`productuom`,`productmaster`.`id`;

-- --------------------------------------------------------

--
-- Structure for view `productwarranty_view`
--
DROP TABLE IF EXISTS `productwarranty_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `productwarranty_view` AS select `productwarranty`.`Category` AS `Category`,`productwarranty`.`ProductCode` AS `ProductCode`,`productmaster`.`ProductDescription` AS `ProductDescription`,`productwarranty`.`FOC` AS `FOC`,`productwarranty`.`ProRataPeriod` AS `ProRataPeriod`,`productwarranty`.`ManufactureDate` AS `ManufactureDate`,`productwarranty`.`ManufactureWarranty` AS `ManufactureWarranty`,`productwarranty`.`ApplicableFormDate` AS `ApplicableFormDate`,`productwarranty`.`Saleswarranty` AS `Saleswarranty`,`productwarranty`.`Kmrun` AS `Kmrun`,`productwarranty`.`oemname` AS `oemname`,`productwarranty`.`id` AS `ID`,`productwarrantyupload`.`Status` AS `Status`,`productwarrantyupload`.`Franchiseecode` AS `Franchiseecode` from ((`productwarranty` left join `productwarrantyupload` on((`productwarranty`.`ProductCode` = `productwarrantyupload`.`Code`))) left join `productmaster` on((`productwarranty`.`ProductCode` = `productmaster`.`ProductCode`))) group by `productwarranty`.`Category`,`productwarranty`.`ProductCode`,`productmaster`.`ProductDescription`,`productwarranty`.`FOC`,`productwarranty`.`ProRataPeriod`,`productwarranty`.`ManufactureDate`,`productwarranty`.`ManufactureWarranty`,`productwarranty`.`ApplicableFormDate`,`productwarranty`.`Saleswarranty`,`productwarranty`.`Kmrun`,`productwarranty`.`oemname`,`productwarranty`.`id`,`productwarrantyupload`.`Status`,`productwarrantyupload`.`Franchiseecode`;

-- --------------------------------------------------------

--
-- Structure for view `proratalogic_view`
--
DROP TABLE IF EXISTS `proratalogic_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `proratalogic_view` AS select `logicmaster`.`category` AS `category`,`logicmaster`.`logiccode` AS `logiccode`,`logicmaster`.`effectivedate` AS `effectivedate`,ifnull(`logicmaster`.`minimum`,0) AS `minimum`,ifnull(`proratalogic`.`min`,0) AS `min`,ifnull(`proratalogic`.`max`,0) AS `max`,ifnull(`proratalogic`.`discount`,0) AS `discount` from (`logicmaster` left join `proratalogic` on(((`logicmaster`.`logiccode` = `proratalogic`.`logiccode`) and (`logicmaster`.`effectivedate` = `proratalogic`.`effectivedate`)))) group by `proratalogic`.`category`,`proratalogic`.`logiccode`,`proratalogic`.`effectivedate`,`proratalogic`.`min`,`proratalogic`.`max`,`proratalogic`.`discount`,`logicmaster`.`minimum`;

-- --------------------------------------------------------

--
-- Structure for view `purchasedetails_view`
--
DROP TABLE IF EXISTS `purchasedetails_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `purchasedetails_view` AS select `purchase_details`.`PurchaseNumber` AS `PurchaseNumber`,`purchase_details`.`PurchaseDate` AS `PurchaseDate`,`purchase_details`.`ProductCode` AS `ProductCode`,`purchase_details`.`ProductDescription` AS `ProductDescription`,`purchase_details`.`Quantity` AS `Quantity`,avg(`purchase_details`.`Rate`) AS `rate`,sum(`purchase_details`.`Amount`) AS `amount`,`purchase_details`.`FranchiseCode` AS `FranchiseCode`,`purchaseledger`.`Taxledger` AS `Taxledger`,sum(`purchaseledger`.`Taxamount`) AS `TaxAmount` from (`purchase_details` left join `purchaseledger` on((`purchase_details`.`PurchaseNumber` = `purchaseledger`.`Purchaseno`))) group by `purchase_details`.`PurchaseNumber`,`purchase_details`.`PurchaseDate`,`purchase_details`.`ProductCode`,`purchase_details`.`ProductDescription`,`purchase_details`.`FranchiseCode`,`purchaseledger`.`Taxledger`;

-- --------------------------------------------------------

--
-- Structure for view `pwview`
--
DROP TABLE IF EXISTS `pwview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `pwview` AS select `d`.`repmasterid` AS `masterid`,`d`.`pricelevel` AS `pricelevel`,`d`.`NewproductSlNo` AS `blsno`,`d`.`Newproductcode` AS `Newproductcode`,`d`.`replacetype` AS `replacetype`,`d`.`dcstatus` AS `dcstatus`,`d`.`replacevocherno` AS `replacevocherno`,`d`.`replacedate` AS `replacedate`,`d`.`billqty` AS `billqty`,`d`.`ProrataAmt` AS `ProrataAmt`,round(sum((case when (`dl`.`Taxledger` not in ('Discount','Prorata Charges')) then `dl`.`Taxamount` else 0 end)),0) AS `Taxamount`,round(sum((case when (`dl`.`Taxledger` in ('Discount','Prorata Charges')) then `dl`.`Taxamount` else 0 end)),0) AS `discount`,`d`.`FranchiseeName` AS `franchisecode`,`d`.`CustomerName` AS `CustomerName` from (`dcwarranty` `d` left join `dcwarrantyledger` `dl` on((`dl`.`masterid` = `d`.`repmasterid`))) where ((`d`.`repmasterid` <> '') and (`d`.`dcstatus` = 'ACTIVE')) group by `d`.`repmasterid`,`d`.`replacedate`,`d`.`FranchiseeName`;

-- --------------------------------------------------------

--
-- Structure for view `sapmle`
--
DROP TABLE IF EXISTS `sapmle`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `sapmle` AS select `productmaster`.`ProductDescription` AS `ProductDescription`,`productmapping`.`MapProductCode` AS `MapProductCode`,`productmapping`.`effectivedate` AS `effectivedate` from (`productmapping` left join `productmaster` on((`productmapping`.`ProductCode` = `productmaster`.`ProductCode`)));

-- --------------------------------------------------------

--
-- Structure for view `servicemasterview`
--
DROP TABLE IF EXISTS `servicemasterview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `servicemasterview` AS select `servicemaster`.`Productcode` AS `ProductCode`,`productmaster`.`ProductDescription` AS `ProductDescription`,`servicemaster`.`EffectiveDate` AS `EffectiveDate`,`servicemaster`.`CompensationValue` AS `CompensationValue` from (`servicemaster` left join `productmaster` on((`servicemaster`.`Productcode` = `productmaster`.`ProductCode`)));

-- --------------------------------------------------------

--
-- Structure for view `view_branch1`
--
DROP TABLE IF EXISTS `view_branch1`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_branch1` AS select `b`.`region` AS `region`,`b`.`id` AS `id`,`b`.`country` AS `country`,`b`.`branchcode` AS `branchcode`,`b`.`branchname` AS `branchname`,`c`.`countryname` AS `countryname`,`r`.`RegionName` AS `RegionName` from ((`branch` `b` left join `countrymaster` `c` on((`b`.`country` = `c`.`countrycode`))) left join `region` `r` on((`b`.`region` = `r`.`RegionCode`))) order by `b`.`id` desc;

-- --------------------------------------------------------

--
-- Structure for view `view_closingstock`
--
DROP TABLE IF EXISTS `view_closingstock`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_closingstock` AS select `slr`.`franchiseecode` AS `franchiseecode`,`slr`.`productcode` AS `productcode`,`slr`.`opdate` AS `opdate`,sum((`sd`.`receipt` - `sd`.`issue`)) AS `StockonHand` from (`stockledgerreport` `slr` left join `stock_day` `sd` on(((`slr`.`franchiseecode` = `sd`.`franchiseecode`) and (`slr`.`productcode` = `sd`.`productcode`)))) where ((`slr`.`mdate` = curdate()) and (`sd`.`stockdate` < `slr`.`opdate`)) group by `slr`.`franchiseecode`,`slr`.`productcode`,`slr`.`opdate`;

-- --------------------------------------------------------

--
-- Structure for view `view_fbr`
--
DROP TABLE IF EXISTS `view_fbr`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_fbr` AS select `f`.`Franchisecode` AS `Franchisecode`,`f`.`id` AS `id`,`f`.`Franchisename` AS `Franchisename`,`f`.`Branch` AS `Branch`,`b`.`branchname` AS `branchname`,`f`.`Region` AS `Region`,`r`.`RegionName` AS `RegionName`,`f`.`city` AS `city`,`f`.`State` AS `statecode` from ((`franchisemaster` `f` left join `branch` `b` on((`b`.`branchcode` = `f`.`Branch`))) left join `region` `r` on((`r`.`RegionCode` = `f`.`Region`)));

-- --------------------------------------------------------

--
-- Structure for view `view_francheese_retailer`
--
DROP TABLE IF EXISTS `view_francheese_retailer`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_francheese_retailer` AS select `f`.`Franchisecode` AS `Franchisecode`,`f`.`id` AS `id`,`r`.`RetailerCode` AS `RetailerCode` from (`franchisemaster` `f` left join `retailermaster` `r` on((`r`.`fmexecutive` = `f`.`Franchisecode`))) group by `f`.`Franchisecode`,`f`.`id`,`r`.`RetailerCode`;

-- --------------------------------------------------------

--
-- Structure for view `view_ftemp`
--
DROP TABLE IF EXISTS `view_ftemp`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_ftemp` AS select `f`.`Franchisecode` AS `franchiseecode`,`td`.`from` AS `f_date` from (`franchisemaster` `f` left join `tempdate` `td` on((`td`.`from` is not null)));

-- --------------------------------------------------------

--
-- Structure for view `view_pgroup`
--
DROP TABLE IF EXISTS `view_pgroup`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_pgroup` AS select `ps`.`ProductSegmentCode` AS `ProductSegmentCode`,`ps`.`ProductSegment` AS `psegmentname`,`ps`.`ProductGroup` AS `pgroupcode`,`ps`.`id` AS `id`,`pg`.`ProductGroup` AS `ProductGroup` from (`productsegmentmaster` `ps` left join `productgroupmaster` `pg` on((`pg`.`ProductCode` = `ps`.`ProductGroup`))) group by `ps`.`ProductSegmentCode`,`ps`.`ProductSegment`;

-- --------------------------------------------------------

--
-- Structure for view `view_pgvehiclemodel`
--
DROP TABLE IF EXISTS `view_pgvehiclemodel`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_pgvehiclemodel` AS select `vm`.`modelcode` AS `modelcode`,`vm`.`modelname` AS `modelname`,`fp`.`ProductCode` AS `ProductCode` from (`vehiclemodel` `vm` join `view_rptproductfin` `fp` on((`fp`.`pgroupcode` = `vm`.`ProductGroup`)));

-- --------------------------------------------------------

--
-- Structure for view `view_pmap`
--
DROP TABLE IF EXISTS `view_pmap`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_pmap` AS select `pm`.`ProductDescription` AS `ProductDescription`,`pm`.`ProductCode` AS `pmpc`,`pmp`.`ProductCode` AS `ProductCode`,`pmp`.`MapProductCode` AS `MapProductCode`,`pmp`.`id` AS `id` from (`productmapping` `pmp` left join `productmaster` `pm` on((`pm`.`ProductCode` = `pmp`.`MapProductCode`))) where (`pmp`.`Status` = 'ACTIVE') order by `pmp`.`id` desc;

-- --------------------------------------------------------

--
-- Structure for view `view_price`
--
DROP TABLE IF EXISTS `view_price`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_price` AS select `pricelistlinkinggrid_view`.`PriceListCode` AS `PriceListCode`,`pricelistlinkinggrid_view`.`pricelistname` AS `pricelistname`,`pricelistlinkinggrid_view`.`Country` AS `Country`,`pricelistlinkinggrid_view`.`State` AS `State`,`pricelistlinkinggrid_view`.`Branch` AS `Branch`,`pricelistlinkinggrid_view`.`Franchisee` AS `Franchisee`,`pricelistlinkinggrid_view`.`effectivedate` AS `effectivedate`,`pricelistlinkinggrid_view`.`applicabledate` AS `applicabledate`,`pricelistlinkinggrid_view`.`productcode` AS `productcode`,`pricelistlinkinggrid_view`.`productdescription` AS `productdescription`,`pricelistlinkinggrid_view`.`mrp` AS `mrp`,`pricelistlinkinggrid_view`.`fprice` AS `fprice`,`pricelistlinkinggrid_view`.`rprice` AS `rprice`,`pricelistlinkinggrid_view`.`iprice` AS `iprice`,`pricelistlinkinggrid_view`.`Status` AS `Status`,`pricelistlinkinggrid_view`.`InsertDate` AS `InsertDate`,`pricelistlinkinggrid_view`.`Deliverydae` AS `Deliverydae`,`pricelistlinkinggrid_view`.`Id` AS `Id` from `pricelistlinkinggrid_view` where ((`pricelistlinkinggrid_view`.`effectivedate` <= curdate()) and (`pricelistlinkinggrid_view`.`Status` = '2')) order by `pricelistlinkinggrid_view`.`effectivedate` desc;

-- --------------------------------------------------------

--
-- Structure for view `view_productdtetails`
--
DROP TABLE IF EXISTS `view_productdtetails`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_productdtetails` AS select `ps`.`ProductCode` AS `pcode`,`ps`.`ProductDescription` AS `pdescription`,`ps`.`ptypecode` AS `ptypecode`,`ps`.`ProductTypeName` AS `ptypename`,`ps`.`segmentcode` AS `psegmentcode`,`ps`.`ProductSegment` AS `psegmentname`,`ps`.`pgroup` AS `pgroupcode`,`pg`.`ProductGroup` AS `pgroupname` from (`view_psegment` `ps` left join `productgroupmaster` `pg` on((`pg`.`ProductCode` = `ps`.`pgroup`))) group by `ps`.`ProductCode`,`ps`.`ProductDescription`,`ps`.`ptypecode`,`ps`.`ProductTypeName`,`ps`.`segmentcode`,`ps`.`ProductSegment`,`ps`.`pgroup`,`pg`.`ProductGroup`;

-- --------------------------------------------------------

--
-- Structure for view `view_producttype`
--
DROP TABLE IF EXISTS `view_producttype`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_producttype` AS select `pm`.`ProductCode` AS `ProductCode`,`pm`.`ProductDescription` AS `ProductDescription`,`pm`.`ProductType` AS `ptypecode`,`pt`.`ProductTypeName` AS `ProductTypeName`,`pt`.`ProductSegment` AS `segmentcode` from (`masterproduct` `pm` left join `producttypemaster` `pt` on((`pt`.`ProductTypeCode` = `pm`.`ProductType`))) group by `pm`.`ProductCode`,`pm`.`ProductDescription`,`pm`.`ProductType`,`pt`.`ProductTypeName`,`pt`.`ProductSegment`;

-- --------------------------------------------------------

--
-- Structure for view `view_psegment`
--
DROP TABLE IF EXISTS `view_psegment`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_psegment` AS select `pd`.`ProductCode` AS `ProductCode`,`pd`.`ProductDescription` AS `ProductDescription`,`pd`.`ptypecode` AS `ptypecode`,`pd`.`ProductTypeName` AS `ProductTypeName`,`pd`.`segmentcode` AS `segmentcode`,`ps`.`ProductSegment` AS `ProductSegment`,`ps`.`ProductGroup` AS `pgroup` from (`view_producttype` `pd` left join `productsegmentmaster` `ps` on((`ps`.`ProductSegmentCode` = `pd`.`segmentcode`))) group by `pd`.`ProductCode`,`pd`.`ProductDescription`,`pd`.`ptypecode`,`pd`.`ProductTypeName`,`pd`.`segmentcode`,`ps`.`ProductSegment`,`ps`.`ProductGroup`;

-- --------------------------------------------------------

--
-- Structure for view `view_purchasereturn`
--
DROP TABLE IF EXISTS `view_purchasereturn`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_purchasereturn` AS select `purchasereturn_details`.`RetDate` AS `RetDate`,`purchasereturn_details`.`ProductCode` AS `ProductCode`,`purchasereturn_details`.`ProductDescription` AS `ProductDescription`,sum(`purchasereturn_details`.`Quantity`) AS `purchaseretqty`,`purchasereturn_details`.`FranchiseCode` AS `FranchiseCode` from `purchasereturn_details` where (`purchasereturn_details`.`voucherstatus` = 'ACTIVE') group by `purchasereturn_details`.`RetDate`,`purchasereturn_details`.`ProductCode`,`purchasereturn_details`.`ProductDescription`,`purchasereturn_details`.`FranchiseCode`;

-- --------------------------------------------------------

--
-- Structure for view `view_rbrs`
--
DROP TABLE IF EXISTS `view_rbrs`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_rbrs` AS select `vf`.`Franchisecode` AS `Franchisecode`,`vf`.`id` AS `id`,`vf`.`Franchisename` AS `Franchisename`,`vf`.`Branch` AS `Branch`,`vf`.`branchname` AS `branchname`,`vf`.`Region` AS `Region`,`vf`.`RegionName` AS `RegionName`,`vf`.`city` AS `city`,`vf`.`statecode` AS `statecode`,`s`.`statename` AS `statename` from (`view_fbr` `vf` left join `state` `s` on((`s`.`statecode` = `vf`.`statecode`)));

-- --------------------------------------------------------

--
-- Structure for view `view_region1`
--
DROP TABLE IF EXISTS `view_region1`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_region1` AS select `r`.`RegionCode` AS `RegionCode`,`r`.`RegionName` AS `RegionName`,`r`.`CountryName` AS `Country`,`c`.`countryname` AS `countryname` from (`region` `r` left join `countrymaster` `c` on((`r`.`CountryName` = `c`.`countrycode`)));

-- --------------------------------------------------------

--
-- Structure for view `view_retailersales`
--
DROP TABLE IF EXISTS `view_retailersales`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_retailersales` AS select `r`.`salesno` AS `salesno`,`r`.`salesdates` AS `salesdates`,`r`.`productcode` AS `productcode`,`r`.`productdes` AS `productdes`,`rs`.`schemename` AS `schemename`,`rs`.`VoucherType` AS `VoucherType`,`rs`.`retailername` AS `retailername`,`rs`.`voucherstatus` AS `voucherstatus`,sum(`r`.`quantity`) AS `quantity`,`f`.`Franchisename` AS `franchisename`,`f`.`Branch` AS `branchcode`,`f`.`Region` AS `regioncode`,`re`.`RegionName` AS `regionname`,`b`.`branchname` AS `branchname`,avg(`r`.`rate`) AS `rate`,sum(`r`.`amount`) AS `amount`,`r`.`franchisecode` AS `franchisecode`,ifnull(`r`.`taxvalue`,0) AS `TaxAmount`,(sum(ifnull(`r`.`taxvalue`,0)) + sum(`r`.`amount`)) AS `grossamt`,`rs`.`pricelevel` AS `pricelevel` from ((((`retailersalesitem` `r` left join `retailersales` `rs` on((`rs`.`salesno` = `r`.`salesno`))) left join `franchisemaster` `f` on((`r`.`franchisecode` = `f`.`Franchisecode`))) left join `branch` `b` on((`b`.`branchcode` = `f`.`Branch`))) left join `region` `re` on((`re`.`RegionCode` = `f`.`Region`))) where (`rs`.`voucherstatus` = 'ACTIVE') group by `r`.`salesno`,`r`.`productcode`,`r`.`franchisecode`;

-- --------------------------------------------------------

--
-- Structure for view `view_retailersalesledger`
--
DROP TABLE IF EXISTS `view_retailersalesledger`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_retailersalesledger` AS select `retailersalesledger`.`SalesNo` AS `SalesNo`,`retailersalesledger`.`salesdates` AS `salesdates`,sum(`retailersalesledger`.`taxamount`) AS `taxamount`,`retailersalesledger`.`franchisecode` AS `franchisecode` from `retailersalesledger` where (`retailersalesledger`.`taxledger` <> 'Discount') group by `retailersalesledger`.`SalesNo`,`retailersalesledger`.`salesdates`,`retailersalesledger`.`franchisecode`;

-- --------------------------------------------------------

--
-- Structure for view `view_rptfranch`
--
DROP TABLE IF EXISTS `view_rptfranch`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_rptfranch` AS select `r`.`RegionCode` AS `RegionCode`,`r`.`RegionName` AS `RegionName`,`r`.`CountryName` AS `CountryName`,`b`.`branchcode` AS `branchcode`,`b`.`branchname` AS `branchname` from (`region` `r` left join `branch` `b` on((`b`.`region` = `r`.`RegionCode`))) group by `r`.`RegionCode`,`r`.`RegionName`,`r`.`CountryName`,`b`.`branchcode`,`b`.`branchname`;

-- --------------------------------------------------------

--
-- Structure for view `view_rptfrnfin`
--
DROP TABLE IF EXISTS `view_rptfrnfin`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_rptfrnfin` AS select `vf`.`RegionCode` AS `RegionCode`,`vf`.`RegionName` AS `RegionName`,`vf`.`CountryName` AS `CountryName`,`vf`.`branchcode` AS `branchcode`,`vf`.`branchname` AS `branchname`,`f`.`Franchisecode` AS `Franchisecode`,`f`.`Franchisename` AS `Franchisename` from (`view_rptfranch` `vf` left join `franchisemaster` `f` on((`f`.`Branch` = `vf`.`branchcode`))) group by `vf`.`RegionCode`,`vf`.`RegionName`,`vf`.`CountryName`,`vf`.`branchcode`,`vf`.`branchname`,`f`.`Franchisecode`,`f`.`Franchisename`;

-- --------------------------------------------------------

--
-- Structure for view `view_rptproduct`
--
DROP TABLE IF EXISTS `view_rptproduct`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_rptproduct` AS select `ps`.`ProductSegmentCode` AS `ProductSegmentCode`,`ps`.`ProductSegment` AS `psegmentname`,`pg`.`ProductCode` AS `pgroupcode`,`pg`.`id` AS `id`,`pg`.`ProductGroup` AS `ProductGroup` from (`productgroupmaster` `pg` left join `productsegmentmaster` `ps` on((`pg`.`ProductCode` = `ps`.`ProductGroup`))) group by `ps`.`ProductSegmentCode`,`ps`.`ProductSegment`;

-- --------------------------------------------------------

--
-- Structure for view `view_rptproductfin`
--
DROP TABLE IF EXISTS `view_rptproductfin`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_rptproductfin` AS select `vt`.`pgroupcode` AS `pgroupcode`,`vt`.`ProductGroup` AS `ProductGroup`,`vt`.`ProductSegmentCode` AS `ProductSegmentCode`,`vt`.`psegmentname` AS `psegmentname`,`vt`.`ProductTypeCode` AS `ProductTypeCode`,`vt`.`ProductTypeName` AS `ProductTypeName`,`pm`.`ProductCode` AS `ProductCode`,`pm`.`ProductDescription` AS `ProductDescription`,`pm`.`id` AS `id` from (`view_rpt_product2` `vt` left join `productmaster` `pm` on((`pm`.`ProductType` = `vt`.`ProductTypeCode`))) group by `vt`.`pgroupcode`,`vt`.`ProductGroup`,`vt`.`ProductSegmentCode`,`vt`.`psegmentname`,`vt`.`ProductTypeCode`,`vt`.`ProductTypeName`,`pm`.`ProductCode`,`pm`.`ProductDescription`,`pm`.`id`;

-- --------------------------------------------------------

--
-- Structure for view `view_rpt_product2`
--
DROP TABLE IF EXISTS `view_rpt_product2`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_rpt_product2` AS select `vr`.`pgroupcode` AS `pgroupcode`,`vr`.`ProductGroup` AS `ProductGroup`,`vr`.`ProductSegmentCode` AS `ProductSegmentCode`,`vr`.`psegmentname` AS `psegmentname`,`pt`.`ProductTypeCode` AS `ProductTypeCode`,`pt`.`ProductTypeName` AS `ProductTypeName` from (`view_rptproduct` `vr` left join `producttypemaster` `pt` on((`pt`.`ProductSegment` = `vr`.`ProductSegmentCode`))) group by `vr`.`pgroupcode`,`vr`.`ProductGroup`,`vr`.`ProductSegmentCode`,`vr`.`psegmentname`,`pt`.`ProductTypeCode`,`pt`.`ProductTypeName`;

-- --------------------------------------------------------

--
-- Structure for view `view_selectregion`
--
DROP TABLE IF EXISTS `view_selectregion`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_selectregion` AS select `b`.`branchname` AS `branchname`,`r`.`RegionCode` AS `regioncode`,`r`.`RegionName` AS `regionname` from (`branch` `b` left join `region` `r` on((`r`.`RegionCode` = `b`.`region`)));

-- --------------------------------------------------------

--
-- Structure for view `view_status`
--
DROP TABLE IF EXISTS `view_status`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_status` AS select `vf`.`Franchisecode` AS `Franchisecode`,`vf`.`Franchisename` AS `Franchisename`,`vf`.`Branch` AS `Branch`,`vf`.`branchname` AS `branchname`,`vf`.`RegionName` AS `RegionName`,max(`ds`.`date`) AS `downloaddate`,max(`up`.`date`) AS `Uploaddate` from ((`view_fbr` `vf` left join `uploadstatus` `up` on((`up`.`franchisecode` = `vf`.`Franchisecode`))) left join `downloadstatus` `ds` on((`ds`.`franchisecode` = `vf`.`Franchisecode`))) group by `vf`.`Franchisecode`;

-- --------------------------------------------------------

--
-- Structure for view `view_vehicledetails`
--
DROP TABLE IF EXISTS `view_vehicledetails`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_vehicledetails` AS select `vm`.`id` AS `id`,`vm`.`modelname` AS `modelname`,`vm`.`MakeName` AS `makecode`,`vm`.`segmentname` AS `segmentcode`,`vmm`.`MakeName` AS `makename`,`vsm`.`segmentname` AS `segmentname` from ((`vehiclemodel` `vm` left join `vehiclemakemaster` `vmm` on((`vm`.`MakeName` = `vmm`.`MakeNo`))) left join `vehiclesegmentmaster` `vsm` on((`vm`.`segmentname` = `vsm`.`segmentcode`))) order by `vm`.`id` desc;

-- --------------------------------------------------------

--
-- Structure for view `view_vmodel`
--
DROP TABLE IF EXISTS `view_vmodel`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_vmodel` AS select `vehiclemodel`.`modelcode` AS `modelcode`,`vehiclemodel`.`modelname` AS `modelname`,`vehiclemakemaster`.`MakeName` AS `MakeName`,`vehiclesegmentmaster`.`segmentname` AS `segmentname`,`productgroupmaster`.`ProductGroup` AS `ProductGroup` from (((`vehiclemodel` left join `vehiclemakemaster` on((`vehiclemodel`.`MakeName` = `vehiclemakemaster`.`MakeNo`))) left join `vehiclesegmentmaster` on((`vehiclemodel`.`segmentname` = `vehiclesegmentmaster`.`segmentcode`))) left join `productgroupmaster` on((`vehiclemodel`.`ProductGroup` = `productgroupmaster`.`ProductCode`))) group by `vehiclemodel`.`modelcode`,`vehiclemodel`.`modelname`,`vehiclemakemaster`.`MakeName`,`vehiclesegmentmaster`.`segmentname`,`productgroupmaster`.`ProductGroup`;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
