{{ partial('navbars/header')}}

<div class="container" style="width:100%;padding-left:0px;padding-right:0px;">
    {{ flash.output() }}
    {{ content() }}
    <hr>
    {{ partial('navbars/footer')}}
</div>
