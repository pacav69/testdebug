Joomla project builder v1.0 for component creation
Created by Paul Cav (pacav69)

Introduction
============
The files that are in this project folder copy files from templates directory for a version 2.5.x joomla project.
This is based on the MVC helloworld v0.0.19 component for v2.5 joomla.

 What it does
 ============
 It copies files from templates directory to componentname development directory.
 It renames the appropiate php files to 'componentname(s).php'.
 Searches for helloworld in the form of all lower case, all upper case and camelcase in all files including php, ini, and xml and replaces with componentname.
 Creates 'componentname.xml' manifest file using the componentname and the values of the properties in 'antconfig.properties' file.

To use
===========
1. Edit 'antconfig.properties' file to suit your requirements.
2. Using ANT run 'createproject.xml' this will copy files from templates directory that are required for the basic component.
3. Edit files in custom directory then run ant file 'addcustom.xml' to copy files to componentname dev directory. 
4. To zip up files for release open ant file 'createproject.xml' and select 'build_component' the files are then stored in 'uploadzip' dir.
5. To upload to local host edit antconfig.properties file and edit the properties 'cfg.localhostRoot=' to your local host location open ant file named 'createproject.xml' and select 'update_localhost'.

Version history
===============
V1.0
Initial release
