export default function useSearching()
{
    let term = ''

    const onSearch = (rows, rawRows, e) => {
        term = e.target.value;
        rows.value = performSearch(rawRows.value, term);
    }

    const performSearch = (rows, term) => {
        const results = rows.filter(
            row => Object.values(row).join(" ").toLowerCase().includes(term.toLowerCase())
        )

        return results;
    }

    return{
        onSearch
    }
}
