<div class="message-container{!! isset($type) ? ' ' . $type : '' !!} alert alert-danger pt10 pb10 pl15 pr15"{!! (count($errors) > 0) ? '' : ' style="display: none;"' !!}>
    @if (count($errors) > 0)
        <?php
        $messages = array_unique($errors->all());
        $errorStr = '';
        ?>
        @foreach ($messages as $message)
            <span class="error">{!! $message !!}</span>
        @endforeach
        @foreach ($errors->messages() as $error => $messages)
            <?php
            if (str_contains($error, '.')) {
                $error = preg_replace('/(.*)(\.)(.*)/', '$1[$3]', $error);
            }
            $errorStr = $errorStr . '$(\'[name=\"' . $error . '\"]\').parents(\'.form-group\').addClass(\'has-error\');';
            ?>
        @endforeach
        @if (!empty($errorStr))
            <script type="text/javascript">
                $('document').ready(function() {
                    eval("{!! $errorStr !!}");
                });
            </script>
        @endif
    @endif
</div>