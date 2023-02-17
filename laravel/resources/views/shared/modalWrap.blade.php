<div class='modal fade' id='{{ $modalId }}' tabindex='-1' role='dialog' aria-labelledby='modalLabel' aria-hidden='true' data-backdrop="static" data-keyboard="false">
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                @isset($title)
                    <h4 class='modal-title text-center' id='modalLabel'>{{ $title }}</h4>
                @endisset
            </div>
            <div class='modal-body text-center'>
                @isset($message)
                    <div class="row">
                        <span>{{ $message }}</span>
                    </div>
                @endisset
                @yield('body')
            </div>
            <div class="modal-footer">
                @yield('buttons')
            </div>
        </div>
    </div>
</div>