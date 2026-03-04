<div class="invora-profile-wrapper">

    <div class="invora-profile-header">
        <div>
            <div class="invora-profile-name">
                Network Tree
            </div>
            <div class="invora-profile-meta">
                Visual representation of your referral structure
            </div>
        </div>
    </div>

    <div class="tree-container">

        <div class="tree-root">
            YOU
        </div>

        <div class="tree-level">

            @foreach($level1 as $ref)

                <div 
                    x-data="{ open:false }"
                    class="tree-node"
                >
                    <div 
                        class="tree-user"
                        @click="open = !open"
                    >
                        {{ $ref->user->name }}
                    </div>

                    <div 
                        x-show="open"
                        x-transition
                        class="tree-children"
                    >
                        @php
                            $children = \App\Models\Referral::where('referred_by_id', $ref->user_id)
                                ->with('user')
                                ->get();
                        @endphp

                        @foreach($children as $child)
                            <div class="tree-user child">
                                {{ $child->user->name }}
                            </div>
                        @endforeach
                    </div>

                </div>

            @endforeach

        </div>

    </div>

</div>