<?php

namespace SixtyEightPublishers\Application\Diagnostics;

use Nette\Utils\Html;
use SixtyEightPublishers\Application\Environment;
use SixtyEightPublishers\Application\Profile;
use SixtyEightPublishers\Application\ProfileContainer;
use Tracy\IBarPanel;


class Panel implements IBarPanel
{
	/** @var \SixtyEightPublishers\Application\ProfileContainer  */
	private $profiles;

	/** @var \SixtyEightPublishers\Application\Environment  */
	private $environment;

	/**
	 * @param \SixtyEightPublishers\Application\ProfileContainer    $profiles
	 * @param \SixtyEightPublishers\Application\Environment         $environment
	 */
	public function __construct(ProfileContainer $profiles, Environment $environment)
	{
		$this->profiles = $profiles;
		$this->environment = $environment;
	}

	/**
	 * @return string
	 */
	public function getTab()
	{
		return (string) Html::el('span title="Environment"')
			->addHtml($this->getIcon())
			->addHtml(Html::el('span class=tracy-label')->setText($this->profiles->getDefaultProfile()->getName())
		);
	}

	/**
	 * @return string
	 */
	public function getPanel()
	{
		$panel = [];
		$panel[] = Html::el('h2')->setText('Configured profiles:');

		$table = Html::el('table');
		$table->addHtml(Html::el('thead')->addHtml(Html::el('tr')
			->addHtml(Html::el('th')->setText('name'))
			->addHtml(Html::el('th')->setText('country'))
			->addHtml(Html::el('th')->setText('language'))
			->addHtml(Html::el('th')->setText('currency'))
			->addHtml(Html::el('th')->setText('domain'))
		));
		$table->addHtml($tbody = Html::el('tbody'));

		/** @var Profile $profile */
		foreach ($this->profiles as $profile)
		{
			$tbody->addHtml($tr = Html::el('tr')
				->addHtml(Html::el('td')->setText($profile->getName()))
				->addHtml(Html::el('td')->setHtml(implode('<br>', $profile->getCountries())))
				->addHtml(Html::el('td')->setHtml(implode('<br>', $profile->getLanguages())))
				->addHtml(Html::el('td')->setHtml(implode('<br>', $profile->getCurrencies())))
				->addHtml(Html::el('td')->setHtml(implode('<br>', $profile->getDomains())))
			);

			if ($profile === $this->environment->getProfile())
				$tr->class[] = 'yes';
		}

		$panel[] = $table;
		$h1 = Html::el('h1')->setText('Environment');

		return $h1 . Html::el('div class="nette-inner tracy-inner sixtyEightPublishers-EnvironmentPanel"')->setHtml(implode(' ', $panel)) . $this->getStyles();
	}

	/**
	 * @return string
	 */
	private function getIcon()
	{
		return '<svg width="19" height="18" xmlns="http://www.w3.org/2000/svg">
			<g>
				<rect fill="none" id="canvas_background" height="20" width="21" y="-1" x="-1"/>
				<g display="none" overflow="visible" y="0" x="0" height="100%" width="100%" id="canvasGrid">
					<rect fill="url(#gridpattern)" stroke-width="0" y="0" x="0" height="100%" width="100%"/>
				</g>
			</g>
			<g>
				<path id="svg_3" d="m13.59522,10.55317l1.93154,-1.04947c-0.03773,-0.38884 -0.11124,-0.76604 -0.20289,-1.13685l-2.14645,-0.30523c-0.18531,-0.45959 -0.43093,-0.88585 -0.71912,-1.27714l0.81813,-2.06646c-0.26914,-0.26944 -0.55955,-0.5151 -0.86514,-0.74114l-1.82249,1.17502c-0.4269,-0.23081 -0.88789,-0.40612 -1.3713,-0.51871l-0.65811,-2.09549c-0.18733,-0.01448 -0.37357,-0.02979 -0.56399,-0.02979s-0.3766,0.01495 -0.56451,0.02979l-0.65212,2.07693c-0.49612,0.10898 -0.96631,0.28633 -1.40469,0.51999l-1.79384,-1.15774c-0.30561,0.22604 -0.59599,0.4717 -0.86514,0.74114l0.79582,2.00939c-0.3104,0.40816 -0.56998,0.85851 -0.76492,1.34387l-2.0783,0.29522c-0.09108,0.37029 -0.16534,0.74709 -0.20274,1.1368l1.86536,1.01368c0.02115,0.5336 0.10751,1.05076 0.25983,1.53774l-1.40751,1.59803c0.16414,0.3487 0.34603,0.68686 0.55746,1.00413l2.07153,-0.45679c0.33973,0.37959 0.72683,0.71216 1.15369,0.9872l-0.07873,2.17708c0.34012,0.15762 0.69396,0.2895 1.05784,0.39495l1.31077,-1.73034c0.2324,0.03049 0.46817,0.05103 0.70824,0.05103c0.26021,0 0.51441,-0.02414 0.76515,-0.05947l1.3172,1.73993c0.36547,-0.10531 0.71818,-0.2372 1.05863,-0.39482l-0.08064,-2.21618c0.40715,-0.2706 0.77596,-0.59434 1.10119,-0.95985l2.12618,0.46844c0.21034,-0.31722 0.39302,-0.65502 0.55648,-1.00403l-1.45265,-1.65033c0.1396,-0.46127 0.21739,-0.94822 0.24022,-1.45051l0,0zm-3.21122,2.52092l-0.91614,0.61245l-0.56314,-0.88904c-0.29284,0.11579 -0.60848,0.18619 -0.94127,0.18619c-1.45259,0 -2.6299,-1.20965 -2.6299,-2.70148c0,-1.49189 1.17731,-2.70108 2.6299,-2.70108c1.45195,0 2.62991,1.20919 2.62991,2.70108c0,0.74551 -0.29408,1.41945 -0.76936,1.90842l0.56001,0.88346z" stroke-width="0.3" stroke="#0f0f00" fill="#4edb27"/>
			</g>
		</svg>';
	}

	/**
	 * @return string
	 */
	private function getStyles()
	{
		return "<style>
			#nette-debug .sixtyEightPublishers-EnvironmentPanel h2, #tracy-debug .sixtyEightPublishers-EnvironmentPanel h2 {
				font-size: 14px;
			}
			#nette-debug .sixtyEightPublishers-EnvironmentPanel tr.yes td, #tracy-debug .sixtyEightPublishers-EnvironmentPanel tr.yes td {
				background: #BDE678;
			}
		</style>";
	}

}