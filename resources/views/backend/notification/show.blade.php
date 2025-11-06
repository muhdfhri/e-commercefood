<div id="notifications">
    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-bell fa-fw"></i>
        <!-- Counter - Alerts -->
        <span class="badge badge-danger badge-counter">
            @if(count(Auth::user()->unreadNotifications) >5 )<span data-count="5" class="count">5+</span>
            @else 
                <span class="count" data-count="{{count(Auth::user()->unreadNotifications)}}">{{count(Auth::user()->unreadNotifications)}}</span>
            @endif
        </span>
      </a>
      <!-- Dropdown - Alerts -->
      <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
        <h6 class="dropdown-header">
          Pusat Notifikasi
        </h6>
        @foreach(Auth::user()->unreadNotifications as $notification)
    <a class="dropdown-item d-flex align-items-center" target="_blank" href="{{route('admin.notification',$notification->id)}}">
                <div class="mr-3">
                    <div class="icon-circle bg-primary">
                    <i class="fas {{$notification->data['fas']}} text-white"></i>
                    </div>
                </div>
                <div>
                    <div class="small text-gray-500">
                        {{ $notification->created_at->locale('id')->isoFormat('dddd, D MMMM Y') }}
                        <span class="time-ago" data-timestamp="{{ $notification->created_at->timestamp }}">
                            {{ $notification->created_at->format('H:i') }} WIB
                        </span>
                    </div>
                    <span class="@if($notification->unread()) font-weight-bold @else small text-gray-500 @endif">{{$notification->data['title']}}</span>
                </div>
            </a>
            @if($loop->index+1==5)
                @php 
                    break;
                @endphp
            @endif
        @endforeach

        <a class="dropdown-item text-center small text-gray-500" href="{{route('all.notification')}}">Lihat Semua Notifikasi</a>
      </div>
</div>

@push('scripts')
<script>
    function updateTimeAgo() {
        document.querySelectorAll('.time-ago').forEach(element => {
            const timestamp = parseInt(element.getAttribute('data-timestamp')) * 1000;
            const now = new Date();
            const notificationTime = new Date(timestamp);
            const diffInSeconds = Math.floor((now - notificationTime) / 1000);
            
            let timeText = '';
            
            if (diffInSeconds < 60) {
                timeText = 'Baru saja';
            } else if (diffInSeconds < 3600) {
                const minutes = Math.floor(diffInSeconds / 60);
                timeText = `${minutes} menit yang lalu`;
            } else if (diffInSeconds < 86400) {
                const hours = Math.floor(diffInSeconds / 3600);
                timeText = `${hours} jam yang lalu`;
            } else {
                // Lebih dari 24 jam, tampilkan jam:menit
                timeText = notificationTime.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                }) + ' WIB';
            }
            
            element.textContent = timeText;
        });
    }
    
    // Update immediately
    updateTimeAgo();
    
    // Update every minute
    setInterval(updateTimeAgo, 60000);
</script>
@endpush