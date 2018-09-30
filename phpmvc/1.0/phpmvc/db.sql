--
--
-- Author           Pierre-Henry Soria <phy@hizup.uk>
-- Copyright        (c) 2015-2017, Pierre-Henry Soria. All Rights Reserved.
-- License          Lesser General Public License <http://www.gnu.org/copyleft/lesser.html>
-- Link             http://hizup.uk
--
--

--
-- Set some SQL Variables --
--

CREATE DATABASE IF NOT EXISTS `cp` default charset utf8 COLLATE utf8_general_ci;

use cp;

set sql_mode="ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION";

SET @sAdminEmail = 'test@test.com';
SET @sAdminPassword = '$2y$14$kefF6aqkuOEWo7CIFduNf.7O8BuGR4uWrIAFcHWm2u99OcLPDFWOe';
SET @sPostTitle = 'My First Post';
SET @sPostBody = 'Hello! Here is my first blog post!!\r\n\r\n\r\nLorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.\r\n\r\nDonec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus.\r\n\r\nAenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui.\r\n\r\nEtiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante.\r\n\r\nEtiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Donec sodales sagittis magna. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc, quis gravida magna mi a libero. Fusce vulputate eleifend sapien. Vestibulum purus quam, scelerisque ut, mollis sed, nonummy id, metus.\r\n\r\nNullam accumsan lorem in dui. Cras ultricies mi eu turpis hendrerit fringilla. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In ac dui quis mi consectetuer lacinia. Nam pretium turpis et arcu. Duis arcu tortor, suscipit eget, imperdiet nec, imperdiet iaculis, ipsum. Sed aliquam ultrices mauris. Integer ante arcu, accumsan a, consectetuer eget, posuere ut, mauris. Praesent adipiscing. Phasellus ullamcorper ipsum rutrum nunc. Nunc nonummy metus. Vestibulum volutpat pretium libero. Cras id dui.';
SET @sPostDate = NOW();


CREATE TABLE IF NOT EXISTS Posts (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  title varchar(50) DEFAULT NULL,
  body longtext NOT NULL,
  createdDate datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (id)
) DEFAULT CHARSET=utf8;

INSERT INTO Posts (title, body, createdDate) VALUES
(@sPostTitle, @sPostBody, @sPostDate);


CREATE TABLE IF NOT EXISTS Admins (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  email varchar(120) NOT NULL,
  password char(60) NOT NULL,
  PRIMARY KEY (id)
) DEFAULT CHARSET=utf8;

INSERT INTO Admins (email, password) VALUES
(@sAdminEmail, @sAdminPassword); -- The admin password is: pwd123
