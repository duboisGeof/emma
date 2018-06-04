















-- Ajout nom et prénom du bénéficiaire
-- update EMMA_ASSURE set(NOM,PRENOM,CLE)
-- =(select nomstd_ben,nomprm_ben,asscle_ben
-- from vben_bdo
-- where NIR=assmac_ben
-- and benqlt_ben='A');
--  REQUETE OK dans Requete IJ . PHP


-- update assures_OD set(NOM,PRENOM)
-- =(select nomstd_ben,nomprm_ben
-- from vben
-- where NIR=assmac_ben
-- and benqlt_ben='A'
-- and NOM is null)
-- where exists (select 'test'
-- from vben
-- where NIR=assmac_ben
-- and benqlt_ben='A'
-- and NOM is null);
-- !!!!!!!! Requete inutile ???? !!!!!!

-- Select '073P',count(unique NIR)
-- From assures_OD
-- Where NOM is null;
-- !!!!!!!! Requete inutile ???? !!!!!!
 
-- Ajout de la civilité du bénéficiaire
-- Alter table assures_OD add(CIV varchar2(3));

-- Update assures_OD set(CIV)
-- =(select civcod_ben
-- from vben_bdo
-- where NIR=assmac_ben
-- and benqlt_ben='A');

-- RAJOUT DANS LA PREMIERE REQUETE  DE REQUETE IJ .PHP

-- Update assures_OD set(CIV)
-- =(select civcod_ben
-- from vben
-- where NIR=assmac_ben
-- and benqlt_ben='A'
-- and CIV is null)
-- where exists
-- (select 'test'
-- from vben
-- where NIR=assmac_ben
-- and benqlt_ben='A'
-- and CIV is null);
-- !!!!!!!! Requete inutile ???? !!!!!!


-- Ajout de l'adresse du bénéficiaire

-- alter table assures_OD add(voinum_001 varchar2(4),voicnu_001 varchar2(3),voityp_001 varchar2(3),
-- voilib_001 varchar2(25),adrcpl_001 varchar2(25),
-- bdicod_001 varchar2(5),rsdlib_001 varchar2(25));

-- update assures_OD set (voinum_001,voicnu_001,voityp_001,voilib_001,adrcpl_001,
-- bdicod_001,rsdlib_001)=(select voinum_adr,voicnu_adr,voityp_adr,voilib_adr,adrcpl_adr,bdicod_adr,rsdlib_adr
-- from vadr_act
-- where NIR=assmac_adr
-- and benqlt_adr='A');  
--  Requete OKI 

-- BEGIN EXECUTE IMMEDIATE ('DROP TABLE prn_3202'); EXCEPTION WHEN OTHERS THEN NULL; END;
-- /

/*create table PRN as
select NIR MAT1,max(prndsf_prn) max_FIN_PRN
from assures_OD,vprn_bdo
where NIR=assmac_prn
group by NIR;

select '037P',count(unique MAT1),count(*)
from PRN;

select '073P',min(max_FIN_PRN) MIN
from PRN;

BEGIN EXECUTE IMMEDIATE ('DROP TABLE assures_prn'); EXCEPTION WHEN OTHERS THEN NULL; END;
/

create table assures_PRN as
select MAT1,prnnat_prn NATURE_PRN,prndsd_prn DEB_PRN,MAX_FIN_PRN FIN_PRN
from PRN,vprn_bdo
where MAT1=assmac_prn
and MAX_FIN_PRN=prndsf_prn;

select '037P',count(unique MAT1),count(*)
from assures_PRN;
*/
-- Ajout des informations sur les PRN
-- alter table assures_OD add(DEB_PRN date,FIN_PRN date,NATURE_PRN varchar2(3));

-- update assures_OD set(DEB_PRN,FIN_PRN,NATURE_PRN)=
--  (select DEB_PRN,FIN_PRN,NATURE_PRN
-- from assures_PRN
-- where MAT1=NIR);

BEGIN EXECUTE IMMEDIATE ('DROP TABLE assures_IJ'); EXCEPTION WHEN OTHERS THEN NULL; END;
/

create table assures_IJ as
select NIR MAT1,exndrd_act DEB_IJ,exndrf_act FIN_IJ,drgnat_dco DRG,entsup_dco SUP
from vact,vdco,assures_OD
where prscat_act='IJ'
and dcoref_act=dcoref_dco
and NIR=assmac_act
and rgusen_dco is null
and benqlt_dco='A'
and exndrd_act=DEB_IJ
group by NIR,exndrd_act,exndrf_act,drgnat_dco,entsup_dco;

select '037P',count(unique MAT1),count(*)
from assures_IJ;

alter table assures_OD add(DEB_PER date,FIN_PER date,DRG varchar2(2),SUP number,NOR varchar2(3));

update assures_OD set(DEB_PER,FIN_PER,DRG,NOR,SUP)=
(select DEB_IJ,FIN_IJ,DRG,NOR,SUP
from assures_IJ
where NIR=MAT1);

BEGIN EXECUTE IMMEDIATE ('DROP TABLE IJ_assures'); EXCEPTION WHEN OTHERS THEN NULL; END;
/

create table IJ_assures as
select NIR MAT,arrasu_ijp asu,perdsd_ijp deb,perdsf_ijp fin,desnum_ijp employ,ijpnbr_ijp nbrij,
ijpmon_ijp montij,revnat_ijp rev
from vijp_bdo,assures_OD
where NIR=assmac_ijp
group by NIR,arrasu_ijp,perdsd_ijp,perdsf_ijp,desnum_ijp,ijpnbr_ijp,ijpmon_ijp,revnat_ijp;

select '036a',count(*),count(unique mat||deb||employ),count(unique mat||deb),count(unique mat)
from IJ_assures;

drop table ij_arrets ;
create table ij_arrets 
	( employ    varchar2(14)
	 ,asu       varchar2(2)
	 ,mat       varchar(13)
	 ,deb 	    date
	 ,fin 	    date
	 ,nbrij     dec(9)
	 ,monttot   dec(9,2)
	) ;

declare
-- curseur (tri par employ/asu/mat/deb/rev) --------------------
  cursor cij_arr1 is 
  select mat
      	,asu
	,deb
	,fin
	,employ
	,nbrij
	,montij
	,rev
  from ij_assures
  order by 5, 2, 1, 3, 8 desc ;

-- variables --------------------------------------------------------------
	---- table ij_arrets-----------------------------------------------
  	wemploy    varchar2(14) default '0' ;
  	wasu    varchar2(2)  default 'X' ;
  	wmat    varchar2(13) default 'X' ;
  	wnbrij     dec(9)       default 0 ;
  	wmonttot   dec(9,2)     default 0 ;
	---- zones de travail----------------------------------------------
  	wdeb    date         default '20991231' ;
  	wfin    date         default '20991231' ;
	-- si wtoprev = 0, le nbr d'ijp est à comptabiliser
	-- si wtoprev = 1, le nbr d'ijp n'est pas à comptabiliser
  	wtoprev dec(3)       default 0 ;
  	lig        cij_arr1%rowtype ;

begin

open cij_arr1 ;

loop 
     fetch cij_arr1 into lig  ;
     exit when cij_arr1%notfound  ;

     if wemploy != lig.employ 
     then 
-----------------------------------------------------------------------------------------
-- changement d'employeur, calcul duree, et insertion de la ligne si non premiere lecture
		if wemploy != '0'
		then 
			 insert into ij_arrets values (wemploy, wasu, wmat, wdeb,wfin, wnbrij, wmonttot) ;
		end if ;

-- init zones, sauvegarde de la ligne en cours de lecture, et calcul des nbr d'ij et du montant
-- calcul montant si montant superieur à zero, cette clause permet aussi de ne pas comptabiliser les nbr d'ij des lignes
-- 		de revalo "pour information" 
-- calcul nbr d'ij : les nbr d'ij indiques sur une ligne de revalo lorsque wtoprev = 1 ne sont pas comptabilises
--                   wtoprev = 1 correspond au cas ou il y a une ligne de revalo 
--
	 	wnbrij     := 0 ;
		wmonttot   := 0 ;
		wtoprev := 0 ;
		wemploy    := lig.employ ;
		wasu    := lig.asu ;
		wmat    := lig.mat ;
		if lig.montij > 0  
		then
			if lig.rev is null 
			then wnbrij     := wnbrij + lig.nbrij ;
				 wtoprev := 1 ;
			else
				if wtoprev = 0
				then wnbrij := wnbrij + lig.nbrij ;
				end if ;
			end if ;
			wmonttot := wmonttot + (lig.montij * lig.nbrij) ;
		end if;
		wdeb  := lig.deb ;
		wfin  := lig.fin ;
     else
		if wasu != lig.asu
		then

-- changement d'assurance, calcul duree, et insertion de la ligne 
			insert into ij_arrets values (wemploy, wasu, wmat,  wdeb,wfin, wnbrij, wmonttot) ;
-- init zones, sauvegarde .............................................................................
		 	wnbrij     := 0 ; 
			wmonttot   := 0 ;
			wtoprev := 0 ;
			wasu    := lig.asu ;
			wmat    := lig.mat ;
			if lig.montij > 0  
		    	then
				if lig.rev is null 
				then  wnbrij := wnbrij + lig.nbrij ;
					  wtoprev := 1 ;
				else
			        if wtoprev = 0
				    then wnbrij := wnbrij + lig.nbrij ;
			        end if ;
				end if ;
				wmonttot := wmonttot + (lig.montij * lig.nbrij) ;
			end if;
			wdeb  := lig.deb ;
			wfin  := lig.fin ;
		else
			if wmat != lig.mat
			then

-- changement d'assure, calcul duree, et insertion de la ligne 
				insert into ij_arrets values (wemploy, wasu, wmat,  wdeb,wfin, wnbrij, wmonttot) ;
-- init zones, sauvegarde ................................................................................
		 	    wnbrij     := 0 ;
			    wmonttot   := 0 ;
			    wtoprev := 0 ;
			    wmat    := lig.mat ;
				if lig.montij > 0  
				then
					if lig.rev is null 
					then wnbrij     := wnbrij + lig.nbrij ;
						 wtoprev := 1 ;
					else
						if wtoprev = 0
						then wnbrij := wnbrij + lig.nbrij ;
						end if ;
					end if ;
					wmonttot := wmonttot + (lig.montij * lig.nbrij) ;
				end if ;
				wdeb  := lig.deb ;
				wfin  := lig.fin ;
			else
				if lig.deb > wfin
				then
					if lig.deb = wfin + 1
					then

-- meme arret faire les sommes si le montant n'est pas à zero
						if lig.montij > 0  
						then
							if lig.rev is null 
							then wnbrij     := wnbrij + lig.nbrij ;
								 wtoprev := 1 ;
							else
								if wtoprev = 0
								then wnbrij   := wnbrij + lig.nbrij ;
								end if ;
							end if ;
							wmonttot := wmonttot + (lig.montij * lig.nbrij) ;
						end if;
						wfin  := lig.fin ;
					else

-- changement d'arret, calcul duree, et insertion de la ligne
						insert into ij_arrets values (wemploy, wasu, wmat,   wdeb,wfin, wnbrij, wmonttot) ;
-- init zones, sauvegarde ...
						wnbrij     := 0 ;
						wmonttot   := 0 ; 
						wtoprev := 0 ; 
					    if lig.montij > 0  
						then 
							if lig.rev is null 
							then wnbrij := wnbrij + lig.nbrij ;
								 wtoprev := 1 ;
							else
								if wtoprev = 0
								then wnbrij := wnbrij + lig.nbrij ;
								end if ;
							end if ;
							wmonttot := wmonttot + (lig.montij * lig.nbrij) ;
						end if;
						wdeb  := lig.deb ;
						wfin  := lig.fin ;
					end if ;
				else

-- date debut inferieure à date fin = periode debute avant la fin du precedent
-- calcul des nbr d'ij et du montant, mais pas de changement pour la date fin
					if lig.montij > 0  
					then
						if lig.rev is null 
						then wnbrij := wnbrij + lig.nbrij ;
							 wtoprev := 1 ;
						else
							if wtoprev = 0
							then wnbrij := wnbrij + lig.nbrij ;
							end if ;
						end if ;
						wmonttot := wmonttot + (lig.montij * lig.nbrij) ;
					end if;
				end if ;
			end if;
		end if;
     end if ;
end loop ;
close cij_arr1 ;

insert into ij_arrets values (wemploy, wasu, wmat,  wdeb,wfin, wnbrij, wmonttot) ;
end ;
/

select '036a',count(*),count(unique mat),count(unique mat||deb),count(unique mat||deb||employ)
from ij_arrets;

alter table assures_OD add(DEB_ARR date);

update assures_OD set(DEB_ARR)=
(select DEB from Ij_arrets
where NIR=MAT
and DEB_PER between DEB and FIN
group by DEB);

alter table assures_OD add(SUBRO varchar2(5),FLUX varchar2(15));

select '073P',DRG,count(*)
from assures_OD
group by DRG
order by 2;

update assures_OD set(SUBRO)='O' where DRG='S';

update assures_OD set(SUBRO)='N' where DRG='A';

select '073P',count(*)
from assures_OD
where SUBRO is null;

select '073P',SUP,count(*)
from assures_OD
group by SUP
order by 2;

update assures_OD set(FLUX)='Progres PN' where SUP=52;

update assures_OD set(FLUX)='PE EFI' where SUP=84;

update assures_OD set(FLUX)='PE EDI Post' where SUP=86;

update assures_OD set(FLUX)='PE EDI M-M' where SUP=87;

select '073P',count(*)
from assures_OD
where FLUX is null;

alter table assures_OD add(CIV2 varchar2(10));

update assures_OD set(CIV2)='MONSIEUR' where CIV='M';

update assures_OD set(CIV2)='MADAME' where CIV in('MME','MLE');

spool off

set colsep ";"

--Résultats

spool D:\IJ_Deuxieme_OD\Ij_2eme_OD

select '073P',NIR,CLE,CIV,NOM,PRENOM,(voinum_001||voicnu_001||' ' ||voityp_001||' '||voilib_001) ADRESSE,
adrcpl_001 CPL_ADRESSE,bdicod_001 CODE_POSTAL,rsdlib_001 VILLE,
TO_CHAR(DEB_ARR,'DD/MM/YYYY') DEB_ARR1,TO_CHAR(FIN_PRN,'DD/MM/YYYY') FIN_PRN1,NATURE_PRN,SUBRO,FLUX,CIV2
from assures_od
group by NIR,CLE,CIV,NOM,PRENOM,voinum_001,voicnu_001,voityp_001,voilib_001,adrcpl_001,
bdicod_001,rsdlib_001,DEB_ARR,FIN_PRN,NATURE_PRN,SUBRO,FLUX,CIV2
order by 2;

spool off

BEGIN EXECUTE IMMEDIATE ('DROP TABLE assures_OD'); EXCEPTION WHEN OTHERS THEN NULL; END;
/

BEGIN EXECUTE IMMEDIATE ('DROP TABLE prn'); EXCEPTION WHEN OTHERS THEN NULL; END;
/

BEGIN EXECUTE IMMEDIATE ('DROP TABLE assures_prn'); EXCEPTION WHEN OTHERS THEN NULL; END;
/

BEGIN EXECUTE IMMEDIATE ('DROP TABLE assures_IJ'); EXCEPTION WHEN OTHERS THEN NULL; END;
/

BEGIN EXECUTE IMMEDIATE ('DROP TABLE IJ'); EXCEPTION WHEN OTHERS THEN NULL; END;
/

exit