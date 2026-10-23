<style>
    .insight { display: grid; gap: 1rem; }
    .insight-card, .traffic-card {
        border: 1px solid rgb(226 232 240);
        border-radius: 1.1rem;
        background: #fff;
        padding: 1.1rem 1.2rem 1.2rem;
        box-shadow: 0 10px 30px rgb(15 23 42 / 5%);
    }
    .dark .insight-card, .dark .traffic-card {
        border-color: rgb(255 255 255 / 10%);
        background: rgb(17 24 39);
        box-shadow: none;
    }
    .insight-head, .insight-person header {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.75rem;
    }
    .insight h2, .traffic-card h2 { margin: 0; font-size: 1rem; font-weight: 700; }
    .insight h3 { margin: 0; font-size: 1.05rem; font-weight: 700; }
    .insight h3 a, .insight table a { color: #15803d; text-decoration: none; }
    .insight-head p, .insight-card > p, .insight-person header p { margin: 0.2rem 0 0; font-size: 0.85rem; opacity: 0.7; }
    .insight-chips { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-top: 0.9rem; }
    .insight-chip {
        border: 0;
        border-radius: 999px;
        background: rgb(241 245 249);
        padding: 0.4rem 0.85rem;
        font-size: 0.82rem;
        font-weight: 650;
    }
    .dark .insight-chip { background: rgb(31 41 55); color: inherit; }
    .insight-chip.is-on { background: #16a34a; color: white; }
    .insight-reset { border: 0; background: transparent; font-size: 0.85rem; text-decoration: underline; opacity: 0.7; }
    .insight-filters {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.75rem;
        margin-top: 0.9rem;
    }
    .insight-filters label { display: grid; gap: 0.3rem; font-size: 0.78rem; font-weight: 700; }
    .insight-filters input, .insight-filters select {
        width: 100%;
        border: 1px solid rgb(226 232 240);
        border-radius: 0.75rem;
        background: rgb(248 250 252);
        padding: 0.55rem 0.7rem;
        font-weight: 500;
    }
    .dark .insight-filters input, .dark .insight-filters select {
        border-color: rgb(55 65 81);
        background: rgb(15 23 42);
        color: inherit;
    }
    .insight-kpis { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 0.75rem; }
    .insight-kpi {
        border: 1px solid rgb(226 232 240);
        border-top: 4px solid var(--kpi);
        border-radius: 1rem;
        background: #fff;
        padding: 0.9rem 1rem 1rem;
    }
    .dark .insight-kpi { border-color: rgb(255 255 255 / 10%); background: rgb(17 24 39); }
    .insight-kpi p, .insight-kpi small { margin: 0; }
    .insight-kpi p { font-size: 0.78rem; font-weight: 700; opacity: 0.65; }
    .insight-kpi strong { display: block; margin: 0.35rem 0; font-size: 1.8rem; line-height: 1; }
    .insight-kpi small { display: block; font-size: 0.75rem; line-height: 1.35; opacity: 0.62; }
    .insight-legend { display: flex; flex-wrap: wrap; gap: 0.8rem; font-size: 0.75rem; font-weight: 700; }
    .insight-legend span { display: inline-flex; align-items: center; gap: 0.35rem; }
    .insight-legend span::before { content: ""; width: 0.7rem; height: 0.7rem; border-radius: 0.2rem; }
    .insight-legend .is-pages::before, .traffic-key-pages::before, .is-pages { background: #16a34a; }
    .insight-legend .is-products::before { background: #0284c7; }
    .insight-legend .is-orders::before { background: #f97316; }
    .insight-split, .insight-lists { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.75rem; }
    .insight-lists { grid-template-columns: repeat(4, minmax(0, 1fr)); }
    .insight-person {
        margin-top: 0.9rem;
        border: 1px solid rgb(226 232 240);
        border-radius: 1rem;
        padding: 0.9rem;
    }
    .dark .insight-person { border-color: rgb(255 255 255 / 10%); }
    .insight-avatar {
        display: grid;
        width: 2.4rem;
        height: 2.4rem;
        place-items: center;
        border-radius: 999px;
        background: #dcfce7;
        color: #166534;
        font-weight: 800;
    }
    .insight-pills { display: flex; flex-wrap: wrap; gap: 0.35rem; }
    .insight-pills span {
        border-radius: 999px;
        background: rgb(241 245 249);
        padding: 0.3rem 0.65rem;
        font-size: 0.75rem;
        font-weight: 700;
    }
    .dark .insight-pills span { background: rgb(31 41 55); }
    .insight-person dl { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 0.6rem; margin: 0.9rem 0 0; }
    .insight-person dt { font-size: 0.72rem; font-weight: 700; opacity: 0.55; }
    .insight-person dd { margin: 0.15rem 0 0; font-size: 0.88rem; }
    .insight-table-wrap { margin-top: 0.8rem; overflow-x: auto; }
    .insight table { width: 100%; border-collapse: collapse; font-size: 0.86rem; }
    .insight th, .insight td { padding: 0.55rem 0.4rem; text-align: start; border-bottom: 1px solid rgb(226 232 240); }
    .dark .insight th, .dark .insight td { border-bottom-color: rgb(255 255 255 / 8%); }
    .insight th { font-size: 0.72rem; letter-spacing: 0.03em; text-transform: uppercase; opacity: 0.6; }
    .insight-subtitle { margin: 1rem 0 0; font-size: 1rem; }
    .insight-empty { margin: 0.8rem 0 0; font-size: 0.9rem; opacity: 0.7; }
    .traffic-chart { display: flex; align-items: end; gap: 0.35rem; margin-top: 1rem; min-height: 12rem; overflow-x: auto; }
    .traffic-chart.is-short { min-height: 8.5rem; }
    .traffic-col { display: flex; min-width: 1.8rem; flex: 1; flex-direction: column; align-items: center; gap: 0.35rem; }
    .traffic-stack { display: flex; align-items: end; justify-content: center; gap: 2px; width: 100%; height: 9rem; }
    .traffic-chart.is-short .traffic-stack { height: 6rem; }
    .traffic-stack span { display: block; width: 0.45rem; border-radius: 0.25rem 0.25rem 0 0; }
    .traffic-stack .is-pages { background: #16a34a; }
    .traffic-stack .is-products { background: #0284c7; }
    .traffic-stack .is-orders { background: #f97316; }
    .traffic-col small { font-size: 0.65rem; opacity: 0.65; white-space: nowrap; }
    .traffic-row { margin-top: 0.75rem; }
    .traffic-row p { display: flex; justify-content: space-between; gap: 0.75rem; margin: 0; font-size: 0.84rem; }
    .traffic-meter { height: 0.45rem; margin-top: 0.28rem; overflow: hidden; border-radius: 999px; background: rgb(226 232 240); }
    .dark .traffic-meter { background: rgb(55 65 81); }
    .traffic-meter span { display: block; height: 100%; border-radius: inherit; background: #16a34a; }
    @media (max-width: 1100px) {
        .insight-kpis, .insight-lists { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .insight-person dl { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 720px) {
        .insight-filters, .insight-kpis, .insight-split, .insight-lists, .insight-person dl { grid-template-columns: 1fr; }
    }
</style>
