<div class="content">
    <div class="title">Something went wrong.</div>
@unless(empty($sentryID))
    <!-- Sentry JS SDK 2.1.+ required -->
        <script src="https://cdn.ravenjs.com/3.3.0/raven.min.js"></script>

        <script>
            Raven.showReportDialog({
                eventId: '{{ $sentryID }}',

                // use the public DSN (dont include your secret!)
                dsn: 'https://84ca08cee5524d68ad5181057c712192@sentry.io/216833'
            });
        </script>
    @endunless
</div>