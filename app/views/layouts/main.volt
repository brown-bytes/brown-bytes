{{ partial('navbars/header')}}

<div class="container">
    {{ flash.output() }}
    {{ content() }}
    <hr>
    {{ partial('navbars/footer')}}
</div>
