@props(['img', 'judul', 'deskripsi'])

<div class="w-[30rem] h-[7rem] bg-[#F6F6F6] p-[2rem] flex mt-[15px] rounded-[10px]">
    <div class="bg-white w-[70px] flex items-center justify-center">
        <img src="{{$img}}" class="w-[2rem] h-[2rem] object-cover">
    </div>
    <div class="ml-[1rem] mt-[-15px]">
        <p class="text-xl">{{$judul}}</p>
        <p class="mt-[2px] text-sm">{{$deskripsi}}</p>
    </div>
</div>
