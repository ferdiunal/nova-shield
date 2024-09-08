<?php

namespace Ferdiunal\NovaShield\Http\Middleware;

use Ferdiunal\NovaShield\Contracts\HasShieldTeam;

class TeamMiddleware
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request):mixed  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {

        if (config('permission.teams', false) && $request->user() instanceof HasShieldTeam) {
            if ($team_id = $request->user()->team_id) {
                setPermissionsTeamId($team_id);
            }
        }

        return $next($request);
    }
}
