<div class="fixed inset-0 z-[9999] pointer-events-none opacity-30 select-none">
    <img src="{{ asset('asset/pengembangan.png') }}" alt="Prototipe" class="w-full h-full object-cover">
</div>

{{-- Auto Fill Bot for Development --}}
@if(config('app.env') !== 'production')
    <script src="{{ asset('js/auto-form-filler.bot.js') }}"></script>
@endif