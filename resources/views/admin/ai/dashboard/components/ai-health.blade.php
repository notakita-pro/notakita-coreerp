{{-- ========================================================= --}}
{{-- 🤖 AI Health --}}
{{-- ========================================================= --}}

<div class="card border-0 shadow-sm">

    <div class="card-header bg-white border-0">

        <h5 class="mb-1">
            🤖 AI Health
        </h5>

        <small class="text-muted">
            Status layanan AI dan integrasi sistem.
        </small>

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-borderless align-middle mb-0">

                <tbody>

                    <tr>

                        <td width="45%">
                            WhatsApp Cloud API
                        </td>

                        <td>

                            @if($health['whatsapp'] ?? false)

                                <span class="badge bg-success">
                                    🟢 Connected
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    🔴 Disconnected
                                </span>

                            @endif

                        </td>

                    </tr>

                    <tr>

                        <td>
                            OpenAI
                        </td>

                        <td>

                            @if($health['openai'] ?? false)

                                <span class="badge bg-success">
                                    🟢 Ready
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    🔴 Offline
                                </span>

                            @endif

                        </td>

                    </tr>

                    <tr>

                        <td>
                            Gemini
                        </td>

                        <td>

                            @if($health['gemini'] ?? false)

                                <span class="badge bg-success">
                                    🟢 Ready
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    🔴 Offline
                                </span>

                            @endif

                        </td>

                    </tr>

                    <tr>

                        <td>
                            ElevenLabs
                        </td>

                        <td>

                            @if($health['elevenlabs'] ?? false)

                                <span class="badge bg-success">
                                    🟢 Ready
                                </span>

                            @else

                                <span class="badge bg-warning text-dark">
                                    🟡 Not Configured
                                </span>

                            @endif

                        </td>

                    </tr>

                    <tr>

                        <td>
                            Queue Worker
                        </td>

                        <td>

                            @if($health['queue'] ?? false)

                                <span class="badge bg-success">
                                    🟢 Running
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    🔴 Stopped
                                </span>

                            @endif

                        </td>

                    </tr>

                    <tr>

                        <td>
                            Media Storage
                        </td>

                        <td>

                            @if($health['storage'] ?? false)

                                <span class="badge bg-success">
                                    🟢 Available
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    🔴 Error
                                </span>

                            @endif

                        </td>

                    </tr>

                    <tr>

                        <td>
                            Database
                        </td>

                        <td>

                            @if($health['database'] ?? false)

                                <span class="badge bg-success">
                                    🟢 Healthy
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    🔴 Error
                                </span>

                            @endif

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>