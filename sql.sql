-- jawaban nomor 1
CREATE TABLE pegawai 
( 
	id INT(11) NOT NULL auto_increment,
	nama_depan VARCHAR(50) NULL , 
	nama_belakang VARCHAR(50) NULL , 
	tanggal_masuk DATE NULL , 
	tanggal_keluar DATE NULL , 
	penghasilan INT(11) NULL , 
	PRIMARY KEY (id)
);

create table kpi 
(
	id INT(11) NOT NULL auto_increment,
	id_pegawai int(11) not null,
	tanggal_review date null,
	primary key (id),
	foreign key (id_pegawai) references pegawai(id)
);

-- jawaban nomor 2
select 
CONCAT(nama_depan, ' ', nama_belakang) as nama,
tanggal_masuk, tanggal_keluar, penghasilan 
from pegawai
where tanggal_keluar is null
order by nama_depan, nama_belakang asc

-- jawwaban nomor 3
select a.*, b.*
from pegawai as a
left join kpi as b
on a.id = b.id_pegawai
where tanggal_masuk = '2021-03-14'
and tanggal_review is null


-- jawaban nomor 4
select * from pegawai
where penghasilan  > 10000
and tanggal_keluar is null
order by penghasilan DESC

-- jawaban nomor 5
select nama_depan, nama_belakang,
tanggal_masuk, tanggal_keluar,
max(penghasilan) as penghasilan
from pegawai
where tanggal_keluar is not null
group by penghasilan
order by penghasilan desc
limit 1