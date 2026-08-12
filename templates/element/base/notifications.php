<?php

/**
 * @var \App\View\AppView $this
 */
?>
<div class="static lg:relative flex align-middle" x-data="{ open: false }">
    <button type="button" aria-label="Notifications" @click="open = !open" class="flex items-center rounded-full relative text-muted-foreground hover:text-foreground p-2 disabled:opacity-50 disabled:cursor-not-allowed">
        <i data-lucide="bell"></i>
        <span class="absolute top-0 right-0 inline-block w-2 h-2 bg-orange-500 rounded-full"></span>
    </button>
    <div x-show="open" x-cloak @click.away="open = false" x-transition class="absolute right-5 lg:right-0 mt-10 w-96 z-50 bg-popover text-popover-foreground border border-border rounded-lg shadow-lg">
        <div class="p-4 border-b border-border font-semibold flex items-center align-middle justify-between">
            <div><span>Notifications</span></div>
            <div class="flex items-center align-middle justify-end gap-2">
                <div class="flex justify-center">
                    <div
                        x-data="{
                            open: false,
                            toggle() {
                                if (this.open) {
                                    return this.close()
                                }

                                this.$refs.button.focus()

                                this.open = true
                            },
                            close(focusAfter) {
                                if (! this.open) return

                                this.open = false

                                focusAfter && focusAfter.focus()
                            }
                        }"
                        x-on:keydown.escape.prevent.stop="close($refs.button)"
                        x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
                        x-id="['dropdown-button']"
                        class="relative"
                    >
                        <!-- Button -->
                        <button
                            x-ref="button"
                            x-on:click="toggle()"
                            :aria-expanded="open"
                            :aria-controls="$id('dropdown-button')"
                            type="button"
                            class="relative flex items-center whitespace-nowrap justify-center p-2 rounded-lg hover:bg-accent hover:text-accent-foreground text-foreground"
                        >
                            <i data-lucide="filter"></i>
                        </button>

                        <!-- Panel -->
                        <div
                            x-ref="panel"
                            x-show="open"
                            x-transition.origin.top.right
                            x-on:click.outside="close($refs.button)"
                            :id="$id('dropdown-button')"
                            x-cloak
                            class="absolute right-0 w-48 rounded-lg shadow-sm mt-2 z-10 origin-top-right bg-popover p-1.5 outline-none border border-border"
                        >
                            <label class="px-2 lg:py-1.5 py-2 w-full flex items-center rounded-md transition-colors text-left text-foreground hover:bg-accent hover:text-accent-foreground focus-visible:bg-accent focus-visible:text-accent-foreground disabled:opacity-50 disabled:cursor-not-allowed">
                                <input name="show_unread" id="show-unread" type="checkbox" value="1" checked class="sr-only peer">
                                <div class="relative w-11 h-6 bg-input peer-focus:outline-none peer-focus:ring-1 peer-focus:ring-ring rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-background after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-popover after:border after:border-border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                <span class="ms-3 text-sm font-medium text-foreground">Show unread</span>
                            </label>
                            <label class="px-2 lg:py-1.5 py-2 w-full flex items-center rounded-md transition-colors text-left text-foreground hover:bg-accent hover:text-accent-foreground focus-visible:bg-accent focus-visible:text-accent-foreground disabled:opacity-50 disabled:cursor-not-allowed">
                                <input name="only_important" id="only-important" type="checkbox" value="" class="sr-only peer">
                                <div class="relative w-11 h-6 bg-input peer-focus:outline-none peer-focus:ring-1 peer-focus:ring-ring rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-background after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-popover after:border after:border-border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                <span class="ms-3 text-sm font-medium text-foreground">Only important</span>
                            </label>
                        </div>
                    </div>
                </div>
                <button class="relative flex items-center whitespace-nowrap justify-center p-2 rounded-lg hover:bg-accent hover:text-accent-foreground text-foreground"><i data-lucide="external-link"></i></button>
                <div class="flex justify-center">
                    <div
                        x-data="{
                            open: false,
                            toggle() {
                                if (this.open) {
                                    return this.close()
                                }

                                this.$refs.button.focus()

                                this.open = true
                            },
                            close(focusAfter) {
                                if (! this.open) return

                                this.open = false

                                focusAfter && focusAfter.focus()
                            }
                        }"
                        x-on:keydown.escape.prevent.stop="close($refs.button)"
                        x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
                        x-id="['dropdown-button']"
                        class="relative"
                    >
                        <!-- Button -->
                        <button
                            x-ref="button"
                            x-on:click="toggle()"
                            :aria-expanded="open"
                            :aria-controls="$id('dropdown-button')"
                            type="button"
                            class="relative flex items-center whitespace-nowrap justify-center p-2 rounded-lg hover:bg-accent hover:text-accent-foreground text-foreground"
                        >
                            <i data-lucide="ellipsis" class="rotate-90"></i>
                        </button>

                        <!-- Panel -->
                        <div
                            x-ref="panel"
                            x-show="open"
                            x-transition.origin.top.right
                            x-on:click.outside="close($refs.button)"
                            :id="$id('dropdown-button')"
                            x-cloak
                            class="absolute right-0 w-48 rounded-lg shadow-sm mt-2 z-10 origin-top-right bg-popover p-1.5 outline-none border border-border"
                        >
                            <a href="#new" class="px-2 lg:py-1.5 py-2 w-full flex items-center align-middle rounded-md gap-2 transition-colors text-left text-foreground hover:bg-accent hover:text-accent-foreground focus-visible:bg-accent focus-visible:text-accent-foreground disabled:opacity-50 disabled:cursor-not-allowed text-sm">
                            <i data-lucide="check"></i>MarK all as read
                            </a>
                            <a href="#edit" class="px-2 lg:py-1.5 py-2 w-full flex items-center align-middle gap-2 rounded-md transition-colors text-left text-foreground hover:bg-accent hover:text-accent-foreground focus-visible:bg-accent focus-visible:text-accent-foreground disabled:opacity-50 disabled:cursor-not-allowed text-sm">
                            <i data-lucide="message-square"></i>Give feedback
                            </a>
                            <a href="#delete" class="px-2 lg:py-1.5 py-2 w-full flex items-center align-middle gap-2 rounded-md transition-colors text-left text-foreground hover:bg-accent hover:text-accent-foreground focus-visible:bg-accent focus-visible:text-accent-foreground disabled:opacity-50 disabled:cursor-not-allowed text-sm">
                            <i data-lucide="settings"></i>Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <ul class="p-2">
            <li class="flex items-center gap-3 px-4 py-3 hover:bg-accent rounded-lg">
                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-secondary text-secondary-foreground font-bold">C</span>
                <div>
                    <div class="text-sm font-medium">CodeRabbit commented on a pull request</div>
                    <div class="text-xs text-muted-foreground">2 days ago</div>
                    <div class="text-xs text-muted-foreground">#comment Fix: Email issue fixed</div>
                </div>
                <span class="ml-auto w-2 h-2 bg-blue-500 rounded-full"></span>
            </li>
            <li class="flex items-center gap-3 px-4 py-3 hover:bg-accent rounded-lg">
                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-secondary text-secondary-foreground font-bold">C</span>
                <div>
                    <div class="text-sm font-medium">CodeRabbit commented on a pull request</div>
                    <div class="text-xs text-muted-foreground">2 days ago</div>
                    <div class="text-xs text-muted-foreground">#comment Fix: Email issue fixed</div>
                </div>
                <span class="ml-auto w-2 h-2 bg-green-500 rounded-full"></span>
            </li>
            <li class="flex items-center gap-3 px-4 py-3 hover:bg-accent rounded-lg">
                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-secondary text-secondary-foreground font-bold">C</span>
                <div>
                    <div class="text-sm font-medium">CodeRabbit commented on a pull request</div>
                    <div class="text-xs text-muted-foreground">2 days ago</div>
                    <div class="text-xs text-muted-foreground">#comment Fix: Email issue fixed</div>
                </div>
                <span class="ml-auto w-2 h-2 bg-red-500 rounded-full"></span>
            </li>
        </ul>
    </div>
</div>
