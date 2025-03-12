<?php

declare(strict_types=1);

namespace App\Services\Uzi;

use App\Models\UziRelationRole;
use Illuminate\Support\Facades\Log;

class UziRoleService
{
    public const ROLES = [
        "01.000" => "Arts",
        "01.002" => "Allergoloog (gesloten register)",
        "01.003" => "Anesthesioloog",
        "01.004" => "Apotheekhoudende huisarts",
        "01.008" => "Bedrijfsarts",
        "01.010" => "Cardioloog",
        "01.011" => "Cardiothoracaal chirurg",
        "01.012" => "Dermatoloog",
        "01.013" => "Arts v. maag-darm-leverziekten",
        "01.014" => "Chirurg",
        "01.015" => "Huisarts",
        "01.016" => "Internist",
        "01.018" => "Keel- neus- en oorarts",
        "01.019" => "Kinderarts",
        "01.020" => "Arts klinische chemie (gesloten register)",
        "01.021" => "Klinisch geneticus",
        "01.022" => "Klinisch geriater",
        "01.023" => "Longarts",
        "01.024" => "Arts-microbioloog",
        "01.025" => "Neurochirurg",
        "01.026" => "Neuroloog",
        "01.030" => "Nucleair geneeskundige",
        "01.031" => "Oogarts",
        "01.032" => "Orthopedisch chirurg",
        "01.033" => "Patholoog",
        "01.034" => "Plastisch chirurg",
        "01.035" => "Psychiater",
        "01.039" => "Radioloog",
        "01.040" => "Radiotherapeut",
        "01.041" => "Reumatoloog",
        "01.042" => "Revalidatiearts",
        "01.045" => "Uroloog",
        "01.046" => "Gynaecoloog",
        "01.047" => "Specialist ouderengeneeskunde",
        "01.048" => "Verzekeringsarts",
        "01.050" => "Zenuwarts (gesloten register)",
        "01.062" => "Internist-Allergoloog (gesloten register)",
        "01.055" => "Arts maatschappij en gezondheid",
        "01.056" => "Arts voor verstandelijk gehandicapten",
        "01.070" => "Jeugdarts",
        "01.071" => "Spoedeisende hulp arts",
        "01.074" => "Sportarts",
        "02.000" => "Tandarts",
        "02.053" => "Orthodontist",
        "02.054" => "Kaakchirurg",
        "03.000" => "Verloskundige",
        "04.000" => "Fysiotherapeut",
        "16.000" => "Psychotherapeut",
        "17.000" => "Apotheker",
        "17.060" => "Ziekenhuisapotheker",
        "17.075" => "Openbaar apotheker",
        "25.000" => "Gezondheidszorgpsycholoog",
        "25.061" => "Klinisch psycholoog",
        "25.063" => "Klinisch neuropsycholoog",
        "30.000" => "Verpleegkundige",
        "30.065" => "Verpl. spec. prev. zorg bij som. aandoeningen",
        "30.066" => "Verpl. spec. acute zorg bij som. aandoeningen",
        "30.067" => "Verpl. spec. intensieve zorg bij som. aandoeningen",
        "30.068" => "Verpl. spec. chronische zorg bij som. aandoeningen",
        "30.069" => "Verpl. spec. geestelijke gezondheidszorg",
        "31.000" => "Orthopedagoog-generalist",
        "79.000" => "Geregistreerd-mondhygiënist",
        "80.000" => "Bachelor medisch hulpverlener",
        "81.000" => "Physician assistant",
        "82.000" => "Klinisch technoloog",
        "83.000" => "Apothekersassistent",
        "84.000" => "Klinisch fysicus",
        "85.000" => "Tandprotheticus",
        "86.000" => "VIG-er",
        "87.000" => "Optometrist",
        "88.000" => "Huidtherapeut",
        "89.000" => "Diëtist",
        "90.000" => "Ergotherapeut",
        "91.000" => "Logopedist",
        "92.000" => "Mondhygiënist",
        "93.000" => "Oefentherapeut Mensendieck",
        "94.000" => "Oefentherapeut Cesar",
        "95.000" => "Orthoptist",
        "96.000" => "Podotherapeut",
        "97.000" => "Radiodiagnistisch laborant",
        "98.000" => "Radiotherapeutisch laborant",
        "99.000" => "Zorgverlener andere zorg",
    ];

    public function getRoleName(string $roleCode): string
    {
        $roleName = self::ROLES[$roleCode] ?? null;
        if ($roleName === null) {
            Log::error("Role code not found: " . $roleCode);
            return $roleCode;
        }
        return $roleName;
    }

    /**
     * @param string[] $roleCodes
     * @return UziRelationRole[]
     */
    public function getRolesByCodes(array $roleCodes): array
    {
        $roles = [];
        foreach ($roleCodes as $roleCode) {
            $roles[] = new UziRelationRole(
                code: $roleCode,
                name: $this->getRoleName($roleCode),
            );
        }
        return $roles;
    }
}
