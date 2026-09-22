export default defineEventHandler(async (event) => {
    
    const {code} = event.context.params;
    const { currencyApiKey } = useRuntimeConfig()

    /* console.log(`currency code received: ${code}`)
    console.log(`currency api key: ${currencyApiKey}`) */

    const uri = `https://api.freecurrencyapi.com/v1/latest?apikey=${currencyApiKey}&currencies=${code}`;

    // console.log(`URL to call currency api with code: ${uri}`)

    const data  = $fetch(uri);

    // console.log(`respond data: ${data}`)
    

    return data
})