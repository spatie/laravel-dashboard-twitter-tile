<x-dashboard-tile :position="$position">
    <ul wire:poll.5s class="divide-y-2">
        @foreach($tweets as $tweet)
            <li class="overflow-hidden py-4">
                <div class="grid gap-2">
                    <div class="grid grid-cols-auto-1 gap-2 items-center">
                        <div class="overflow-hidden w-10 h-10 rounded-full relative">
                            <img
                                src="{{ $tweet->authorAvatar() }}"
                                class="block w-10 h-10 object-cover filter-gray"
                                style="filter: contrast(75%) grayscale(1) brightness(150%)"
                            />
                            <div class="absolute inset-0 bg-accent opacity-25"></div>
                        </div>
                        <div class="leading-tight min-w-0">
                            <h2 class="truncate font-bold">{{ $tweet->authorName() }}</h2>
                            <div class="truncate text-sm text-dimmed">{{ $tweet->authorScreenName() }}</div>
                        </div>
                    </div>
                    <div>
                        <div class="{{ $tweet->displayClass() }}">{{ $tweet->html() }}</div>
                        <div class="mt-1 text-xs text-dimmed">
                            @if ($tweet->date())
                                {{ $tweet->date()->diffForHumans() }}
                            @endif
                            @if ($tweet->hasQuote())
                                <span> In reply to {{ $tweet->quote()->authorScreenName() }} </span>
                            @endif
                        </div>
                    </div>

                    @if($tweet->image())
                        <img alt="tweet image" class="max-h-48 mx-auto" style="objection-fit: cover;"
                             src="{{ $tweet->image() }}"/>
                    @endif

                    @if ($tweet->hasQuote())
                        <div class="py-2 pl-2 text-xs text-dimmed border-l-2 border-screen">
                            {{ $tweet->quote()->html() }}
                        </div>
                    @endif
                </div>
            </li>
        @endforeach
    </ul>
</x-dashboard-tile>
