--Repositorios que no bajo
select count(*) from ( SELECT distinct id_repositorio FROM `resultadobusquedarepositorio` where id_repositorio not in (select id from repositorio) ) as L ;

--maximo teorico de repositorio a obtener
select sum(total_count) as total_count from ( select busqueda, case when total_count >1000 then 1000 else total_count end as total_count from ( select busqueda,max(total_count) as total_count from busqueda group by busqueda having total_count >0 ) as f ) as a