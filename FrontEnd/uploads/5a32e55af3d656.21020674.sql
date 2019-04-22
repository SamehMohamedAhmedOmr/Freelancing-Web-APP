create  table JOB(
JOB_ID                          VARCHAR(10) NOT NULL primary key,
 JOB_TITLE                   VARCHAR(35) NOT NULL,
 MIN_SALARY                NUMBER(6),
 MAX_SALARY                     NUMBER(6)
);
insert into job values (9,'job1',10,100);
insert into job values (10,'job2',25,180);
insert into job values (11,'job3',20,190);
insert into job values (12,'job4',30,170);
select * from job;
select * from EMPLOYEE;

set serveroutput on ;
create or replace procedure assign(i_ide EMPLOYEE.EMPNO%TYPE,i_idj job.JOB_ID%TYPE)
as
minsalary number;
maxsalary number;
ssalary number;
begin
select MIN_SALARY,max_salary,SALARY
into
minsalary,maxsalary,ssalary
 from job,EMPLOYEE 
 where i_idj=JOB_ID and EMPNO=i_ide;
if(ssalary between minsalary and maxsalary)
then
DBMS_OUTPUT.PUT_LINE('that salary is accepted');
end if;
if(ssalary<minsalary)
then
update EMPLOYEE set EMPLOYEE.SALARY=minsalary where EMPNO=i_ide;
DBMS_OUTPUT.PUT_LINE('that salary has been changed to the minmum');
end if;
if(ssalary>maxsalary)
then
update EMPLOYEE set EMPLOYEE.SALARY=maxsalary where EMPNO=i_ide;
DBMS_OUTPUT.PUT_LINE('that salary has been changed to the maximum');
end if;
end;

set serveroutput on ;
begin
assign(7839,9);
end;


select object_name, object_type from user_objects;

create or replace procedure insertNewEmpy(id number , name varchar2)
as
super_id number:= 3;
begin
INSERT into EMP_TEST VALUES(id , super_id , name);
/*DBMS_OUTPUT.PUT_LINE('your id: ' || id || ',   your name: ' || name || '   , your super id: ' || id_super);*/
end;

set SERVEROUTPUT ON;
BEGIN
insertNewEmpy(20,'shrouk');
END;
/***************************************/
create or replace procedure getemp(id number)
as
name EMP_TEST.ENAME%TYPE;
super_id EMP_TEST.EMP_SUPER%TYPE;
count_check number;
begin
select count(*) into count_check  from EMP_TEST WHERE EMPNO=id;
if(count_check<1)
then 
DBMS_OUTPUT.PUT_LINE('Not Found ');
else
select ENAME , EMP_SUPER into name , super_id from EMP_TEST WHERE EMPNO=id;
DBMS_OUTPUT.PUT_LINE('Name = ' || name || 'super id = ' || super_id );
end if;
end;

set serveroutput on;
begin
getemp(2);
end;

/*********************************************************************/
create or replace procedure getAllemps
as
count_check number;
begin
select count(*) into count_check  from EMP_TEST;
if(count_check<1)
then 
DBMS_OUTPUT.PUT_LINE('Not Found ');
else
for i in(select ENAME , EMPNO from EMP_TEST)
loop
DBMS_OUTPUT.PUT_LINE('Name = ' || i.ENAME || ' ,  id = ' || i.EMPNO );
end loop;
end if;
end;

set serveroutput on;
begin
getAllemps();
end;

/*************capitalize all Names********/
create or replace procedure updateNames
as
begin
for i in(select ENAME , EMPNO from EMP_TEST )
loop
update EMP_TEST set ENAME= LOWER(i.ENAME) where EMPNO = i.EMPNO;
DBMS_OUTPUT.PUT_LINE('Name = ' || i.ENAME || ',  updated to --->  = ' || LOWER(i.ENAME) );
end loop;
end;

begin
updateNames;
end;

