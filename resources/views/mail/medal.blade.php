<x-mail::message>
# Congratulations, {{ $name }}!

You've achieved a new milestone! 🎉

@switch($medal)
    @case('gold')
        You have earned the **Gold Medal** for reaching 500 points! 🥇
        @break

    @case('silver')
        You have earned the **Silver Medal** for reaching 300 points! 🥈
        @break

    @case('bronze')
        You have earned the **Bronze Medal** for reaching 100 points! 🥉
        @break

    @default
        You've made great progress! Keep going!
@endswitch

## Summary:
- **Full Name**: {{ $name }}
- **Email**: {{ $email }}
- **Birthdate**: {{ $birthdate }}
- **Your Message**: {{ $message_content }}

<x-mail::button :url="''">
View Your Achievements
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>