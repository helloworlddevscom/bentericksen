@if($data['display'])
    <li class="{{ implode(' ', $data['classes']) }}">
        @if(isset($data['children']) && !empty($data['children']) && $data['enabled'])
            <a href="{{ $data['url'] }}" class="dropdown-toggle" data-toggle="dropdown"><i class="fa navigation_img {{ $data['icon'] }}"></i>{{ $data['label'] }}
            </a>
            <ul class="dropdown-menu">
                @foreach($data['children'] as $child)
                    <li @if(!$child['display'])class="hidden"@endif>
                        @if($child['enabled'])
                            @if (!empty($child['attributes']))
                                <a href="{{ $child['url'] }}" class="{{ implode(' ', $child['classes']) }}" {{ implode(' ', $child['attributes']) }}>{{ $child['label'] }}</a>
                            @else
                                <a href="{{ $child['url'] }}" class="{{ implode(' ', $child['classes']) }}">{{ $child['label'] }}</a>
                            @endif
                        @else
                            <span class="nav-slot">{{ $child['label'] }}</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            @if($data['enabled'])
                <a href="{{ $data['url'] }}"><i class="fa navigation_img {{ $data['icon'] }}"></i>{{ $data['label'] }}
                </a>
            @else
                <span class="nav-slot"><i class="fa navigation_img {{ $data['icon'] }}"></i>{{ $data['label'] }}</span>
            @endif
        @endif
    </li>
@endif
