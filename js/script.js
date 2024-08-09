//menambah atau menghapus kls CSS pada komponen tertentu ketika tombol di klik
//memunculkan menghilangkan search form
searchForm = document.querySelector('.search-form');                

//mencari tombol dg id=search-btn. Ketika tombol di klik, akan menambahkan kelas active -
//ke search-form kalo belum ada, tapi kl udah ada akan dihapus
document.querySelector('#search-btn').onclick = () =>{          
    searchForm.classList.toggle('active');
}

window.onscroll = () =>{
    // saat scroll akan mengubah kelas active di pencarian menjadi muncul/hilang
    searchForm.classList.toggle('active');

    //klo scroll 80px ke bawah, kelas header-2 akan aktif dan mengubah tampilannya
    if(window.scrollY > 80){
        document.querySelector('.header .header-2').classList.add('active')
    }else{
        document.querySelector('.header .header-2').classList.remove('active')
    }
}

window.onload = () =>{
    if(window.scrollY > 80){
        document.querySelector('.header .header-2').classList.add('active')
    }else{
        document.querySelector('.header .header-2').classList.remove('active')
    }
}

//mengatur tampilan header-2 berdasar posisi scroll di page, baik saat di scroll atau saat onload
