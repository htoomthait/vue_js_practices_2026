export  default defineEventHandler(async (event) => {

    // handle query params
    // const { name } = getQuery(event)

    // handle post data
    // const { age } = await readBody(event)


    // api call with private key
    const {data} = await $fetch('https://api.freecurrencyapi.com/v1/latest?apikey=fca_live_fldwOO7LM5C7YpxJ6ZXF97IUJQzToNYgkGOCC3Nu')


    return {
        // message: `Hello, ${name}! You are age ${age} years old.`
       data
    }
})